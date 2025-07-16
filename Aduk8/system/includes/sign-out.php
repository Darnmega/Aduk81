<?php
session_start();

require_once("connection.php");

$checkSession = $conn->prepare("SELECT * FROM session_management WHERE session_no = ?");
$checkSession->bind_param('s', $_SESSION['session_no']);
$checkSession->execute();
$results = $checkSession->get_result();
if($results->num_rows > 0){
    $updateSession = $conn->prepare('UPDATE `session_management` SET `session_end`= NOW() AND session_status = 0  WHERE session_no = ?');
    $updateSession->bind_param('s', $_SESSION['session_no']);
    $updateSession->execute();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Check if the request is AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode([
        'status' => true,
        'url' => '/Aduk8/sign-in.php',
        'message' => 'You have been logged out successfully.'
    ]);
} else {
    // Redirect for normal requests
    header('Location: /Aduk8/sign-in.php');
}
exit;
