<?php
header('Content-Type: application/json');

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

require_once "../includes/authenticate.php";
// Validate input data
if (empty($data) || !is_array($data)) {
    cleanOutput();
    response(false, 'Invalid or no data received');
}

if($data['action'] == 'registerNewAdmin'){
    registerAdmin($conn, $data);
}else if($data['action'] == 'registerNewschool'){
    registerSchool($conn, $data);
}else if($data['action'] == 'registerNewsubject'){
    registerSubject($conn, $data);
}
else if($data['action'] == 'registerNewGradeLevels'){
    registerGradeLevels($conn, $data);
}else{
    response(false, 'invalid request ');
}

function cleanOutput() {
    $output = ob_get_contents();
    ob_end_clean();
    
    if (!empty($output)) {
        error_log("Unexpected output detected: " . $output);
        header_remove();
        header('Content-Type: application/json');
        die(json_encode([
            'status' => false,
            'message' => 'Server error',
            'errors' => ['server' => 'Unexpected output']
        ]));
    }
}

function checkUsername($conn, $username){
    $check = $conn->prepare("SELECT * FROM user_creds WHERE username = ?");
    $check->bind_param("s", $username);
    if (!$check->execute()) {
        throw new Exception("Failed to check username: " . $check->error);
    }
    
    $check->store_result();
    if ($check->num_rows > 0) 
    {
        cleanOutput();
        response(false, 'Username already exists', null, [
            'fields' => ['username'],
            'message' => 'This username is already taken.'
        ]);
    }
    $check->close();
}

function response($status, $message, $url = null, $errors = []) {
    http_response_code($status ? 200 : 400);
    echo json_encode([
        'status' => $status ? true : false,
        'message' => $message,
        'url' => $url,
        'errors' => $errors
    ]);
    exit;
}
function checkUserPrefix($gender, $marital) {
    
    if ($gender == 'male') {
        $prefix = 'Mr.';
    } elseif ($gender == 'female') {
        if ($marital == 'married') {
            $prefix = 'Mrs.'; 
        } else {
            $prefix = 'Ms.'; 
        }
    } else {
        $prefix = ''; 
    }
    return $prefix;
}

function registerAdmin($conn, $data)
{
    function createId($conn) {
        $currentYear = date('Y');
        $get_admins = $conn->prepare('SELECT COUNT(user_id) as users FROM system_admins');
        $get_admins->execute();
        
        // Fix: Use get_result() before fetch_assoc()
        $result = $get_admins->get_result();
        $admins = $result->fetch_assoc();
        
        $id_no = $admins['users'] + 1;
                
        $duplicate = true;
        $new_id = '';
    
        while($duplicate) {
            $padded_id = str_pad($id_no, 3, '0', STR_PAD_LEFT);
            $new_id = 'ADM' . $padded_id . $currentYear;
            
            $check_duplicate = $conn->prepare('SELECT user_id FROM system_admins WHERE user_id = ?');
            $check_duplicate->bind_param('s', $new_id);
            $check_duplicate->execute();
            
            // Fix: Also use get_result() here
            $result = $check_duplicate->get_result();
            
            if($result->num_rows > 0) {
                $id_no++;
            } else {
                $duplicate = false;
            }
            $check_duplicate->close();
        }
        
        return $new_id;
    }

    try {  
        $first_name = ucwords(strtolower($data['first_name'])); 
        $middle_name = ucwords(strtolower($data['middle_name']));
        $last_name = ucwords(strtolower($data['last_name']));
        $prefix = checkUserPrefix($data['gender'], $data['marital']);





        checkUsername($conn, $data['username']);
    
        // Generate unique user ID
        $user_id = createId($conn);
        $role = "System Administrator";

        // Hash password
        $hashedPassword = password_hash($data['pass'], PASSWORD_DEFAULT);

        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare all statements
            $insert_adm_info = $conn->prepare("INSERT INTO system_admins (user_id, _on) VALUES (?, NOW())");
            $insert_user_info = $conn->prepare("INSERT INTO user_information 
                (user_id, prefix, first_name, middle_name, last_name,gender, date_of_birth, nationality, id_number, marital_status) 
                VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_user_contacts = $conn->prepare("INSERT INTO user_contacts 
                (user_id, phone, email_address, physical_address, postal_address, place_of_residence) 
                VALUES (?, ?, ?, ?, ?, ?)");    
            $insert_user_creds = $conn->prepare("INSERT INTO user_creds 
                (user_id, username, password, hint, role,acc_status) 
                VALUES (?, ?, ?, ?, ?,1)");

            // Check for prepare errors
            $errors = [];
            if (!$insert_adm_info) $errors[] = "system_admins prepare failed: " . $conn->error;
            if (!$insert_user_info) $errors[] = "user_information prepare failed: " . $conn->error;
            if (!$insert_user_contacts) $errors[] = "user_contacts prepare failed: " . $conn->error;
            if (!$insert_user_creds) $errors[] = "user_creds prepare failed: " . $conn->error;
            
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }

            // Bind parameters and execute
            $insert_adm_info->bind_param("s", $user_id);
            $insert_user_info->bind_param("ssssssssss", 
                $user_id, 
                $prefix,
                $first_name, 
                $middle_name,
                $last_name, 
                $data['gender'],
                $data['DOB'],
                $data['nationality'],
                $data['id_number'],
                $data['marital']
            );
            $insert_user_contacts->bind_param("ssssss", 
                $user_id, 
                $data['phone'], 
                $data['email'] ,
                $data['physical'] , 
                $data['postal'],
                $data['residence']
            );
            $insert_user_creds->bind_param("sssss", 
                $user_id, 
                $data['username'],
                $hashedPassword,
                $data['hint'],
                $role
            );

            // Execute all inserts
            $results = [
                'system_admins' => $insert_adm_info->execute(),
                'user_information' => $insert_user_info->execute(),
                'user_contacts' => $insert_user_contacts->execute(),
                'user_creds' => $insert_user_creds->execute()
            ];

            // Check for execution errors
            foreach ($results as $table => $result) {
                if (!$result) {
                    throw new Exception("Insert into $table failed: " . $conn->error);
                }
            }

            // Commit transaction
            $conn->commit();

            // Close statements
            $insert_adm_info->close();
            $insert_user_info->close();
            $insert_user_contacts->close();
            $insert_user_creds->close();
            cleanOutput();
            response(true, 'System Administrator Registration successful!', 'system-admin.php');

        } catch (Exception $e) {
            $conn->rollback();
            cleanOutput();
            response(false, 'Database error: ' . $e->getMessage());
        }

    } catch (Exception $e) {
        cleanOutput();
        response(false, 'Error: ' . $e->getMessage());
    }
}

function registerSchool($conn, $data)
{

    try {
        $center_no = strtoupper($data['center_no']); 
        $sch_name = ucwords(strtolower($data['name']));
        // Check if username exists
        checkUsername($conn, $data['username']);

        $checkcenterNo = $conn->prepare("SELECT * FROM school_info WHERE center_no = ?");
        $checkcenterNo->bind_param("s", strtoupper($center_no));
        if (!$checkcenterNo->execute()) {
            throw new Exception("Failed to check username: " . $checkcenterNo->error);
        }
        
        $checkcenterNo->store_result();
        if ($checkcenterNo->num_rows > 0) 
        {
            cleanOutput();
            response(false, 'Username already exists', null, [
                'fields' => ['username'],
                'message' => 'This username is already taken.'
            ]);
        }
        $checkcenterNo->close();
    
        // Generate unique user ID
        $role = "School Account";

        // Hash password
        $hashedPassword = password_hash($data['pass'], PASSWORD_DEFAULT);

        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare all statements
            $insert_school_info = $conn->prepare("INSERT INTO  school_info (`center_no`, `school_name`, `classification`, `est_date`, `school_motto`, `_on`) VALUES (?, ?, ?, ?, ?, NOW())");
            $insert_school_contacts = $conn->prepare("INSERT INTO `school_contacts`(`center_no`, `phone`, `email_address`, `postal_address`, `physical_address`, `website`, `location`)VALUES (?, ?, ?, ?, ?, ?, ?)");    
            $insert_school_creds = $conn->prepare("INSERT INTO user_creds (user_id, username, password, hint, role) VALUES (?, ?, ?, ?, ?)");

            // Check for prepare errors
            $errors = [];
            if (!$insert_school_info) $errors[] = "School information prepare failed: " . $conn->error;
            if (!$insert_school_contacts) $errors[] = "School contacts prepare failed: " . $conn->error;
            if (!$insert_school_creds) $errors[] = "School_credentials prepare failed: " . $conn->error;
            
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }

            // Bind parameters and execute
            $insert_school_info->bind_param("sssss", 
                $center_no, 
                $sch_name, 
                $data['classification'],
                $data['est_date'], 
                $data['motto'],
            );
            $insert_school_contacts->bind_param("sssssss", 
                $center_no, 
                $data['phone'], 
                $data['email'] ,
                $data['postal'], 
                $data['physical'] ,
                $data['website'],
                $data['location']
            );
            $insert_school_creds->bind_param("sssss", 
                $center_no, 
                $data['username'],
                $hashedPassword,
                $data['hint'],
                $role
            );

            // Execute all inserts
            $results = [
                'school_info' => $insert_school_info->execute(),
                'school_contacts' => $insert_school_contacts->execute(),
                'user_creds' => $insert_school_creds->execute()
            ];

            // Check for execution errors
            foreach ($results as $table => $result) {
                if (!$result) {
                    throw new Exception("Insert into $table failed: " . $conn->error);
                }
            }

            // Commit transaction
            $conn->commit();

            // Close statements
            $insert_school_info->close();
            $insert_school_contacts->close();
            $insert_school_creds->close();
            cleanOutput();
            response(true, 'School Registration successful!', 'school.php');

        } catch (Exception $e) {
            $conn->rollback();
            cleanOutput();
            response(false, 'Database error: ' . $e->getMessage());
        }

    } catch (Exception $e) {
        cleanOutput();
        response(false, 'Error: ' . $e->getMessage());
    }
}
function registerSubject($conn, $data)
{
    function checkSubjectCode($conn, $subject_code){
        $check = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
        $check->bind_param("s", $subject_code);
        if (!$check->execute()) {
            throw new Exception("Failed to check subject code: " . $check->error);
        }
        
        $check->store_result();
        if ($check->num_rows > 0) 
        {
            cleanOutput();
            response(false, 'subject code already exists', null, [
                'fields' => ['subject_code'],
                'message' => 'This subject code is already registered.'
            ]);
        }
        $check->close();
    }

    try {
        $subject_code = strtoupper($data['subject_code']);
        $subject_name = ucwords(strtolower($data['name']));
        // Check if username exists
        checkSubjectCode($conn, $subject_code);

        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare all statements
            $insert_subject_info = $conn->prepare("INSERT INTO `subjects`(`subject_code`, `subject_name`, `classification`, `description`, `_on`) VALUES ( ?, ?, ?, ?, NOW())");

            // Check for prepare errors
            $errors = [];
            if (!$insert_subject_info) $errors[] = "School information prepare failed: " . $conn->error;

            
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }
            

            // Bind parameters and execute
            $insert_subject_info->bind_param("ssss", 
                $subject_code, 
                $subject_name, 
                $data['classification'],
                $data['description']
            );
            // Execute all inserts
            $results = [
                'subjects' => $insert_subject_info->execute()

            ];

            // Check for execution errors
            foreach ($results as $table => $result) {
                if (!$result) {
                    throw new Exception("Insert into $table failed: " . $conn->error);
                }
            }

            // Commit transaction
            $conn->commit();

            // Close statements
            $insert_subject_info->close();
            cleanOutput();
            response(true, 'Subject Registration successful!', 'subjects.php');

        } catch (Exception $e) {
            $conn->rollback();
            cleanOutput();
            response(false, 'Database error: ' . $e->getMessage());
        }

    } catch (Exception $e) {
        cleanOutput();
        response(false, 'Error: ' . $e->getMessage());
    }
}
function registerGradeLevels($conn, $data)
{
    function checkGradeName($conn, $grade_name){
        $check = $conn->prepare("SELECT * FROM grade_levels WHERE grade_name = ?");
        $check->bind_param("s", $grade_name);
        if (!$check->execute()) {
            throw new Exception("Failed to check subject code: " . $check->error);
        }
        
        $check->store_result();
        if ($check->num_rows > 0) 
        {
            cleanOutput();
            response(false, 'subject code already exists', null, [
                'fields' => ['grade_name'],
                'message' => 'This Grade Level Name is already registered.'
            ]);
        }
        $check->close();
    }
    function generate_gradeno($conn) {
        $duplicate = true;
        
        do {
            $grade_no = bin2hex(random_bytes(10)); // 20-character hex string
            
            $checkDuplicate = $conn->prepare('SELECT grade_no FROM grade_levels WHERE grade_no = ?');
            $checkDuplicate->bind_param('s', $grade_no);
            $checkDuplicate->execute();
            $checkDuplicate->store_result();
            
            $duplicate = ($checkDuplicate->num_rows > 0);
            $checkDuplicate->close();
    
        } while ($duplicate);        
        return $grade_no; // Return the generated session ID
    }

    try {
        $grade_code = generate_gradeno($conn);
        $grade_name = strtoupper($data['grade_name']);
        // Check if username exists
        checkGradeName($conn, $grade_name);

        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare all statements
            $insert_grade_level = $conn->prepare("INSERT INTO `grade_levels`(`grade_no`, `grade_name`, `grade_level`, `description`, `_on`) VALUES ( ?, ?, ?, ?, NOW())");

            // Check for prepare errors
            $errors = [];
            if (!$insert_grade_level) $errors[] = "School information prepare failed: " . $conn->error;

            
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }
            

            // Bind parameters and execute
            $insert_grade_level->bind_param("ssss", 
                $grade_code, 
                $grade_name, 
                $data['grade_position'],
                $data['description']
            );
            // Execute all inserts
            $results = [
                'grade_levels' => $insert_grade_level->execute()

            ];

            // Check for execution errors
            foreach ($results as $table => $result) {
                if (!$result) {
                    throw new Exception("Insert into $table failed: " . $conn->error);
                }
            }

            // Commit transaction
            $conn->commit();

            // Close statements
            $insert_grade_level->close();
            cleanOutput();
            response(true, 'Grade Level Registration successful!', 'grade-levels.php');

        } catch (Exception $e) {
            $conn->rollback();
            cleanOutput();
            response(false, 'Database error: ' . $e->getMessage());
        }

    } catch (Exception $e) {
        cleanOutput();
        response(false, 'Error: ' . $e->getMessage());
    }
}