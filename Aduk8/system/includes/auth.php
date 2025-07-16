<?php
include("urls.php");
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once("connection.php");

// Constants for response messages and redirects
define('MAX_LOGIN_ATTEMPTS', 5);
define('SESSION_EXPIRY_HOURS', 4);


/**
 * Standardized JSON response function
 */
function respond($redirect, $message, $status) {
    http_response_code($status ? 200 : 401);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'url' => $redirect
    ]);
    exit();
}

/**
 * Generates and registers a unique session ID
 */
function generateAndRegisterUniqueSessionID($conn, $userId, $startTime, $expiryTime) {
    $maxAttempts = 10;
    $attempts = 0;
    
    do {
        if ($attempts++ >= $maxAttempts) {
            throw new Exception("Failed to generate unique session ID after $maxAttempts attempts");
        }
        
        $sessionId = bin2hex(random_bytes(16));
        $stmt = $conn->prepare('SELECT id FROM session_management WHERE session_no = ?');
        $stmt->bind_param('s', $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
    } while ($result->num_rows > 0);

    $stmt = $conn->prepare("INSERT INTO session_management 
                          (user_id, session_no, session_start, session_end, session_status) 
                          VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("ssss", $userId, $sessionId, $startTime, $expiryTime);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to register session: " . $stmt->error);
    }
    
    return $sessionId;
}

/**
 * Terminates all active sessions for a user
 */
function terminateExistingSessions($conn, $userId, $currentTime, $excludeSessionId = null) {
    $sql = "UPDATE session_management SET session_end = ?, session_status = 0 
            WHERE user_id = ? AND session_status = 1";
    
    if ($excludeSessionId) {
        $sql .= " AND session_no != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $currentTime, $userId, $excludeSessionId);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $currentTime, $userId);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to terminate sessions: " . $stmt->error);
    }
    
    return $stmt->affected_rows;
}

/**
 * Records login attempts with improved error handling
 */
function recordLoginAttempt($conn, $userId, $username, $status, $message, $time, $userAgent, $ipAddress, $deviceId) {
    // Ensure userId is null if not provided
    $userId = $userId ?: null;
    
    try {
        $stmt = $conn->prepare('INSERT INTO loggin_management(device_id, attempted_username, user_id, loggin_status,responce_message, _on, user_agent, ip_address, _on_date) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        
        if (!$stmt) 
        {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param('ssssssss', $deviceId, $username, $userId, $status, 
                         $message, $time, $userAgent, $ipAddress);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Failed to record login attempt: " . $e->getMessage());
        return false;
    }
}

/**
 * Checks for suspicious login patterns
 */
function checkSuspiciousActivity($conn, $username, $ipAddress, $deviceId) {
    // Check recent failed attempts from this IP
    $stmt = $conn->prepare("SELECT COUNT(*) as attempts 
                           FROM loggin_management 
                           WHERE ip_address = ? AND loggin_status = 0 
                           AND _on > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->bind_param("s", $ipAddress);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['attempts'] >= MAX_LOGIN_ATTEMPTS) {
        return "Too many failed login attempts. Please try again later.";
    }
    
    // Check for device anomalies if we have a user
    $stmt = $conn->prepare("SELECT user_id FROM user_creds WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();
    
    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $userId = $user['user_id'];
        
        $stmt = $conn->prepare("SELECT DISTINCT device_id FROM loggin_management 
                               WHERE user_id = ?
                               ORDER BY _on DESC LIMIT 3");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $knownDevices = [];
        while ($row = $result->fetch_assoc()) {
            $knownDevices[] = $row['device_id'];
        }
        
        if (!empty($knownDevices) && !in_array($deviceId, $knownDevices)) {
            return "Login attempt from unrecognized device.";
        }
    }
    
    return null;
}

// Main execution
try {
    // Validate input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data || !isset($data['username']) || !isset($data['password'])) {
        respond($signInFile, 'Invalid request format', false);
    }
    
    // Get client information
    $username = trim($data['username']);
    $password = $data['password'];
    $userAgent = $_SERVER['HTTP_USER_AGENT']; // Fixed typo here
    $ipAddress = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    $deviceId = hash('sha256', $userAgent . $ipAddress);
    $currentTime = date('Y-m-d H:i:s');
    $expiryTime = date('Y-m-d H:i:s', strtotime("+" . SESSION_EXPIRY_HOURS . " hours"));
    
    // Initialize database transaction
    $conn->begin_transaction();
    
    // Check for suspicious activity before processing
    if ($warning = checkSuspiciousActivity($conn, $username, $ipAddress, $deviceId)) {
        // Record attempt before responding
        $recorded = recordLoginAttempt($conn, null, $username, 0, $warning, $currentTime, $userAgent, $ipAddress, $deviceId);
        $conn->commit(); // Commit even for failed attempts
        respond($signInFile, $warning, false);
    }
    
    // Verify user credentials
    $stmt = $conn->prepare("SELECT * FROM user_creds WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows <= 0) {
        $attempt_log = recordLoginAttempt($conn, null, $username, 0, 'Username not found', $currentTime, $userAgent, $ipAddress, $deviceId);
        $conn->commit(); // Commit the attempt record
        respond($signInFile, 'Invalid username credentials', false);
    }
    
    $user = $result->fetch_assoc();
    
    // Check account status
    if ($user['acc_status'] !== '1') {
        recordLoginAttempt($conn, $user['user_id'], $username, 0, 'Account not active', $currentTime, $userAgent, $ipAddress, $deviceId);
        $conn->commit(); // Commit the attempt record
        respond($signInFile, 'Your account is not active', false);
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        recordLoginAttempt($conn, $user['user_id'], $username, 0, 'Invalid password', $currentTime, $userAgent, $ipAddress, $deviceId);
        $conn->commit(); // Commit the attempt record
        respond($signInFile, 'Invalid credentials', false);
    }
    
    // Terminate any existing sessions
    terminateExistingSessions($conn, $user['user_id'], $currentTime);
    
    // Create new session
    $sessionId = generateAndRegisterUniqueSessionID($conn, $user['user_id'], $currentTime, $expiryTime);
    
    // Start PHP session
    session_start();
    $_SESSION = [
        'user_id' => $user['user_id'],
        'role' => $user['role'],
        'session_no' => $sessionId,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'last_activity' => time()
    ];
    //Set the path to all user dashboards
    $schoolAccountDashboard = '/Aduk8/system/accounts/school-account/dashboard.php';
    $schoolSystemAdminDashboard = '/Aduk8/system/accounts/school-account/dashboard.php';
    $systemAdminDashboard = '/Aduk8/system/accounts/system-admin/dashboard.php';
    $studentsDashboard = '/Aduk8/system/accounts/school-account/dashboard.php';
    $guardiansDashboard = '/Aduk8/system/accounts/school-account/dashboard.php';
    $teacherDashboard = '/Aduk8/system/accounts/school-account/dashboard.php';
    
    // Determine redirect based on role
    switch ($user['role']) {
        case 'Student':
            $redirectUrl = $studentsDashboard;
            break;
        case 'Guardian':
           $redirectUrl = $guardiansDashboard;
            break;
        case 'School Account':
            $redirectUrl = $schoolAccountDashboard;
            break;
        case 'School System Administrator':
           $redirectUrl = $schoolSystemAdminDashboard;
            break;
        case 'System Administrator':
           $redirectUrl = $systemAdminDashboard;
            break;
        case 'Teacher':
           $redirectUrl = $teacherDashboard;
            break;
        default:
            throw new Exception("Unknown user role: " . $user['role']);
    }
    
    
    if (!$redirectUrl) {
        throw new Exception("No redirect URL configured for role: " . $user['role']);
    }
    
    // Record successful login
    recordLoginAttempt($conn, $user['user_id'], $username, 1, 'Login successful', $currentTime, $userAgent, $ipAddress, $deviceId);
    $conn->commit();
    
    respond($redirectUrl, 'Login successful', true);
    
} catch (Exception $e) {
    if (isset($conn) && method_exists($conn, 'rollback')) {
        $conn->rollback();
    }
    error_log("System Error: " . $e->getMessage());
    respond($signInFile, 'A system error occurred. Please try again.', false);
}