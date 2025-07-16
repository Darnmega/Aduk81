<?php
require('urls.php');

function kickout($url, $message) {
    // Output JavaScript that shows alert then redirects
    echo '<script>
        alert("'.addslashes($message).'");
        window.location.href = "'.htmlspecialchars($url).'";
    </script>';
    exit;
}
function authenticateSession($conn, $session,$user,$signInFile){
    $check = $conn->prepare('SELECT * FROM session_management WHERE session_no = ? AND session_end >= NOW() AND session_status = 1');
    $check->bind_param('s', $session);
    $check->execute();
    $sessionResults = $check->get_result();
    if($sessionResults->num_rows < 1){
        kickout($signInFile, 'Session Expired Please Login');

    }
    $sessionInformation = $sessionResults->fetch_assoc();
  
    if($sessionInformation['user_id'] !== $user){
        kickout($signInFile, 'Unable to authenticate your session, please login again');
    }

    $check_status = $conn->prepare('SELECT * FROM user_creds WHERE  user_id = ? AND acc_status = 1');
    $check_status->bind_param('s', $user);
    $check_status->execute();
    $check_statusResults = $check_status->get_result();

    if($check_statusResults->num_rows < 0){
        kickout($signInFile, 'Your Account Is Not Active, Please Contact System Administrators');
    }
    return $sessionInformation['user_id'];
}
session_start();
$baseDir = $_SERVER['DOCUMENT_ROOT'];
require_once '/home/deadshot/Desktop/Projects/Aduk8-1/Aduk8/system/includes/connection.php';
#require_once __DIR__ . '/../../../includes/connection.php';#/home/deadshot/Desktop/Aduk8 PHP/system/includes/connection.php
if(!isset($_SESSION['session_no']) || empty($_SESSION['session_no'])|| !isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || !isset($_SESSION['role']) || empty($_SESSION['role']) || isset($_SESSION['role']) != "School Account" )
{
    kickout($signInFile, 'Please login to access the system');
}

// authenticate users current session
$center_no = authenticateSession($conn, $_SESSION['session_no'], $_SESSION['user_id'],$signInFile);
if(!isset($center_no) || empty($center_no)){
    kickout($signInFile, 'Unable to authenticate your session, please login again');
}

//Get the users information
$getSchoolInfo = $conn->prepare('SELECT * FROM  school_information  WHERE center_no = ?');
$getSchoolInfo->bind_param('s',$center_no);
$getSchoolInfo->execute();
$getSchoolInfoResults = $getSchoolInfo->get_result();
if($getSchoolInfoResults->num_rows < 1){
    kickout($signInFile, 'Unable to get your school information, please contact system administrators');
}
$schoolInformation = $getSchoolInfoResults->fetch_assoc();
    
if(!isset($schoolInformation) || empty($schoolInformation)){
    kickout($$signInFile, 'Unable to get your school information, please contact system administrators');
}

function getGradeLevels($conn, $center_no,$exists) {
    $get_grade_levels = $conn->prepare("SELECT * FROM grade_levels WHERE grade_no". $exists ."(SELECT grade_no FROM school_grade_levels  WHERE center_no = ?)");
    $get_grade_levels->bind_param("s", $center_no);
    $get_grade_levels->execute();
    $get_grade_level_results = $get_grade_levels->get_result();
    
    if ($get_grade_level_results->num_rows > 0) {
        $grade_levels = array();
        while ($row = $get_grade_level_results->fetch_assoc()) {
            $grade_levels[] = $row;
        }
        return $grade_levels;
    } else {
        return array(
            "message" => "No grades available for selection.",
            "value" => null
        );
    }
}
function getSchoolSubjects($conn, $center_no,$exists,$status) {
    $get_school_subjects = $conn->prepare("SELECT * FROM subjects WHERE subject_code ". $exists . "(SELECT subject_code FROM school_subjects  WHERE center_no = ? AND status = ?) ORDER BY classification");
    $get_school_subjects->bind_param("ss", $center_no,$status);
    $get_school_subjects->execute();
    $get_school_subjects_results = $get_school_subjects->get_result();
    
    if ($get_school_subjects_results->num_rows > 0) {
        $school_subjects = array();
        while ($row = $get_school_subjects_results->fetch_assoc()) {
            $school_subjects[] = $row;
        }
        return $school_subjects;
    } else {
        return array(
            "message" => "No Subjects available for selection.",
            "value" => null
        );
    }
}
function getClassRange($conn, $center_no,$exists) {
    $get_class_range = $conn->prepare("SELECT * FROM house_class_range WHERE house_no ". $exists . "(SELECT house_no FROM school_houses  WHERE center_no = ?)");
    $get_class_range->bind_param("s", $center_no);
    $get_class_range->execute();
    $get_class_range_results = $get_class_range->get_result();
    
    if ($get_class_range_results->num_rows > 0) {
        $class_range = array();
        while ($row = $get_class_range_results->fetch_assoc()) {
            $class_range[] = $row;
        }
        return $class_range;
    } else {
        return array(
            "message" => "No Classes available",
            "value" => null
        );
    }
}

function getSpecifiedSchoolRooms($conn, $center_no,$exists,$room_use,$room_vacancy): array {
    $get_rooms = $conn->prepare("SELECT * FROM block_class_rooms WHERE room_status = ? AND room_use = ? AND room_no ".$exists." (SELECT room_no from school_blocks where center_no = ?)");
    $get_rooms->bind_param("sss", $room_vacancy,$room_use, $center_no);
    $get_rooms->execute();
    $get_rooms_results = $get_rooms->get_result();
    
    if ($get_rooms_results->num_rows > 0) {
        $rooms = array();
        while ($row = $get_rooms_results->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    } else {
        return array(
            "message" => "No Rooms available for selection.",
            "value" => null
        );
    }
}
function getAllSchoolRooms($conn, $center_no,$exists): array {
    $get_rooms = $conn->prepare("SELECT * FROM block_class_rooms WHERE block_no ".$exists." (SELECT block_no from school_blocks where center_no = ?)");
    $get_rooms->bind_param("s", $center_no);
    $get_rooms->execute();
    $get_rooms_results = $get_rooms->get_result();
    
    if ($get_rooms_results->num_rows > 0) {
        $rooms = array();
        while ($row = $get_rooms_results->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    } else {
        return array(
            "message" => "No Rooms available for selection.",
            "value" => null
        );
    }
}
function getSchoolUserlist($conn,  $center_no,$table){
    $get_rooms = $conn->prepare("SELECT * FROM " . $table . " WHERE center_no = ?");
    $get_rooms->bind_param("s", $center_no);
    $get_rooms->execute();
    $get_rooms_results = $get_rooms->get_result();
    
    if ($get_rooms_results->num_rows > 0) {
        $rooms = array();
        while ($row = $get_rooms_results->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    } else {
        return array(
            "message" => "No Users found",
            "value" => null
        );
    }

}

function getInformationUsingID($conn,$user,$table,$field){
    $get_user = $conn->prepare("SELECT * FROM ". $table ." WHERE " .$field. " = ? ");
    $get_user->bind_param("s",$user);
    $get_user->execute();
    $get_user_results = $get_user->get_result();
    if ($get_user_results->num_rows > 0) {
        $user = array();
        while ($row = $get_user_results->fetch_assoc()) {
            $user[] = $row;
        }
        return $user;
    } else {
        return array(
            "message" => "Information Not found",
            "value" => null
        );
    }
}

function authenticateUserRelation($conn, $user, $center_no, $table,$field1, $field2){
    $get_confirmation = $conn->prepare("SELECT * FROM  ". $table ." WHERE " . $field1 . " = ? AND " . $field2 . " = ?");
    $get_confirmation->bind_param("ss",$user, $center_no);
    $get_confirmation->execute();
    $get_rooms_results = $get_confirmation->get_result();
    
    if ($get_rooms_results->num_rows > 0) {
        $rooms = array();
        while ($row = $get_rooms_results->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    } else {
        kickout('staff-list.php', 'User Not Matched with your school');
    }

}

function getSchoolContacts($conn, $center_no){
    $get_school_contacts = $conn->prepare("SELECT * FROM school_contacts WHERE center_no = ?");
    $get_school_contacts->bind_param("s", $center_no);
    $get_school_contacts->execute();
    $get_school_contacts_results = $get_school_contacts->get_result();
    
    if ($get_school_contacts_results->num_rows > 0) {
        $school_contacts = array();
        while ($row = $get_school_contacts_results->fetch_assoc()) {
            $school_contacts[] = $row;
        }
        return $school_contacts;
    } else {
        return array(
            "message" => "No Contacts Information Found.",
            "value" => null
        );
    }

}

function getSchoolStaff($conn, $center_no,$occupation) {
    $get_staff = $conn->prepare("SELECT * FROM user_information WHERE user_id IN (SELECT staff_id FROM school_staff where center_no = ? AND occupation = ? AND status = 1)");
    $get_staff->bind_param("ss", $center_no,$occupation);
    $get_staff->execute();
    $get_staff_results = $get_staff->get_result();
    
    if ($get_staff_results->num_rows > 0) {
        $staff = array();
        while ($row = $get_staff_results->fetch_assoc()) {
            $staff[] = $row;
        }
        return $staff;
    } else {
        return array(
            "message" => "No $occupation found",
            "value" => null
        );
    }
}

function getGradesForExistingClasses($conn, $center_no){
    $get_grades = $conn->prepare("SELECT * FROM grade_levels WHERE grade_no IN(SELECT grade_no FROM classes WHERE center_no = ? AND graduation_year >= now())");
    $get_grades->bind_param("s", $center_no);
    $get_grades->execute();
    $get_grades_results = $get_grades->get_result();
    
    if ($get_grades_results->num_rows > 0) {
        $grades = array();
        while ($row = $get_grades_results->fetch_assoc()) {
            $grades[] = $row;
        }
        return $grades;
    } else {
        return array(
            "message" => "No grade levels found for the existing classes",
            "value" => null
        );
    }

}
function getActiveClasses($conn, $center_no){
    $getActiveClasses = $conn->prepare("SELECT * FROM classes WHERE center_no = ? AND graduation_year >= now()");
    $getActiveClasses->bind_param("s", $center_no);
    $getActiveClasses->execute();
    $getActiveClassesResults = $getActiveClasses->get_result();
    
    if ($getActiveClassesResults->num_rows > 0) {
        $grades = array();
        while ($row = $getActiveClassesResults->fetch_assoc()) {

            $getClassGrade = $conn->prepare("SELECT * FROM grade_levels WHERE grade_no = ?");
            $getClassGrade->bind_param("s", $row['grade_no']);
            $getClassGrade->execute();
            $classGradeInfo = $getClassGrade->get_result()->fetch_assoc();

            $getClassRange = $conn->prepare("SELECT * FROM house_class_range WHERE range_no = ?");
            $getClassRange->bind_param("s", $row['range_no']);
            $getClassRange->execute();
            $classRangeInfo = $getClassRange->get_result()->fetch_assoc();


            $grade = array(
                "class_no" => $row['class_no'],
                "class_name" => $classGradeInfo['grade_name'] . ' ' . $classRangeInfo['name'].' | '.$row['start_year']
            );
        }
        return $grades;
    } else {
        return array(
            "message" => "No active classes found",
            "value" => null
        );
    }

}
function getStudentsNotInClasses($conn, $center_no){
    $get_staff = $conn->prepare("SELECT * FROM school_students WHERE center_no = ? AND student_id NOT IN (SELECT student_id  FROM student_class where student_class_status != 1 )");
    $get_staff->bind_param("s", $center_no);
    $get_staff->execute();
    $get_staff_results = $get_staff->get_result();
    
    if ($get_staff_results->num_rows > 0) {
        $staff = array();
        while ($row = $get_staff_results->fetch_assoc()) {
            $get_student_info = $conn->prepare("SELECT * FROM user_information WHERE user_id = ?");
            $get_student_info->bind_param("s", $row['student_id']);
            $get_student_info->execute();
            $student_info_results = $get_student_info->get_result()->fetch_assoc();
            $staff[] = $student_info_results;
        }
        return $staff;
    } else {
        return array(
            "message" => "No Students found",
            "value" => null
        );
    }

}