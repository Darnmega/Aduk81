<?php
include('urls.php');

function kickout($url, $message) {
    // Output JavaScript that shows alert then redirects
    echo '<script>
        alert("'.addslashes($message).'");
        window.location.href = "'.htmlspecialchars($url).'";
    </script>';
    exit;
}
function logUserOut(string $message, string $url, bool $useHeaderRedirect = true) {

    // Sanitize inputs
    $sanitizedMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $sanitizedUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

    // Clear session data securely
    $_SESSION = [];
    session_destroy();
    // Clear output buffers
    while (ob_get_level() > 0) {
        if (!ob_end_clean()) {
            break; // Prevent infinite loop if cleanup fails
        }
    }

    // Prepare the JavaScript redirect
    $jsRedirect = "<script>
        alert(" . json_encode($sanitizedMessage) . ");
        window.location.href = " . json_encode($sanitizedUrl) . ";
    </script>";

    // Send appropriate content type header
    //header('Content-Type: text/html; charset=UTF-8');

        echo $jsRedirect;
    

    exit();
}
function checkSessionStatus($conn, $session_no,$signInFile)
{
    $checkSessionStatus = $conn->prepare('SELECT * FROM session_management WHERE session_no = ? AND session_status = 1 AND session_end >= NOW()');
    $checkSessionStatus->bind_param('s', $session_no);
    $checkSessionStatus->execute();
    $checkSessionStatusResults = $checkSessionStatus->get_result();
    if($checkSessionStatusResults->num_rows < 1){
        logUserOut('No active session found',  $signInFile);
    }else{
        while($user = $checkSessionStatusResults->fetch_assoc()){
            return $user['user_id'];
        }
    }
}
session_start();
require_once '/home/deadshot/Desktop/Projects/Aduk8-1/Aduk8/system/includes/connection.php';
#require_once __DIR__ . '/../../../includes/connection.php';#/home/deadshot/Desktop/Aduk8 PHP/system/includes/connection.php
if(!isset($_SESSION['session_no']) && $_SESSION['role'] != "System Administrator" )
{
    kickout('/sign-in.php', 'Please login to access the system');
}

$session_no = $_SESSION['session_no'];
// authenticate users current session
$userId = checkSessionStatus($conn, $session_no,$signInFile);





//Get the users information
$get_users_info = $conn->prepare('SELECT * FROM user_information WHERE user_id = ?');
$get_users_info->bind_param('s',$_SESSION['user_id']);
$get_users_info->execute();
$user_info = $get_users_info->get_result()->fetch_assoc();
