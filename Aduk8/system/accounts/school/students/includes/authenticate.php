<?php
$nationalities = array(
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", 
    "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", 
    "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", 
    "Belarus", "Belgium", "Belize", "Benin", "Bhutan", 
    "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", 
    "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", 
    "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", 
    "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", 
    "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", 
    "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", 
    "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", 
    "Eswatini (fmr. Swaziland)", "Ethiopia", "Fiji", "Finland", "France", 
    "Gabon", "Gambia", "Georgia", "Germany", "Ghana", 
    "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", 
    "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", 
    "India", "Indonesia", "Iran", "Iraq", "Ireland", 
    "Israel", "Italy", "Jamaica", "Japan", "Jordan", 
    "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", 
    "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", 
    "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", 
    "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
    "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", 
    "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", 
    "Mozambique", "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal", 
    "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", 
    "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", 
    "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", 
    "Philippines", "Poland", "Portugal", "Qatar", "Romania", 
    "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", 
    "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", 
    "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", 
    "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", 
    "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", 
    "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", 
    "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", 
    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", 
    "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", 
    "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", 
    "Vietnam", "Yemen", "Zambia", "Zimbabwe"
);
$genders = array(
    'male','female',
);
$current_datetime = date('Y-m-d H:i:s');
function kickout($url, $message) {
    // Output JavaScript that shows alert then redirects
    echo '<script>
        alert("'.addslashes($message).'");
        window.location.href = "'.htmlspecialchars($url).'";
    </script>';
    exit;
}
function authenticateSession($conn, $session,$user){
    $check = $conn->prepare('SELECT * FROM session_log WHERE session_code = ? AND user_id = ? AND expiration_datetime < NOW()');
    $check->bind_param('ss', $session, $user);
    $check->execute();
    $sessionResults = $check->get_result();
    if($sessionResults->num_rows > 0){
        kickout('/sign-in.php', 'Session Expired Please Login');
    }

    $check_status = $conn->prepare('SELECT * FROM user_credentials WHERE  user_id = ? AND acc_status != "Active"');
    $check_status->bind_param('s', $user);
    $check_status->execute();
    $check_statusResults = $check_status->get_result();
    if($check_statusResults->num_rows > 0){
        kickout('/sign-in.php', 'Your Account Is Not Active, Please Contact System Administrators');
    }
}
session_start();
$baseDir = $_SERVER['DOCUMENT_ROOT'];
require_once ($baseDir.'/system/includes/connection.php');
if(!isset($_SESSION['code']) && !isset($_SESSION['role']) && $_SESSION['role'] != "Student" )
{
    kickout('/sign-in.php', 'Please login to access the system');
}

// authenticate users current session
authenticateSession($conn, $_SESSION['code'], $_SESSION['user_id']);
$UserID = $_SESSION['user_id'];


#These are all the files contained in the system 

// base files
$base_url = '/system/';
$base_dir_assets = $base_url.'assets/';
$base_dir_uploads = $base_url.'accounts/uploads/';
$base_dir_learning_resources = $base_url.'accounts/uploads/files/learning-resources';
$base_dir_userfile_uploads = $base_url.'accounts/uploads/files/usr-docs/';

//authenticate.php
$path_to_authenticate = $base_url.'accounts/school/students/includes/authenticate.php';
// path files
$path_to_user_domain = $base_url.'accounts/school/students/';
$profile_pic_url = $base_dir_uploads.'img/usr-pics/';

// Dashboard
$dashboard_url = $path_to_user_domain.'dashboard.php';

//Registration
$new_student_url = $path_to_user_domain.'registration/student.php';
$new_staff_url = $path_to_user_domain.'registration/staff.php';
$new_guardians_url = $path_to_user_domain.'registration/guardians.php';
$new_subject_url = $path_to_user_domain.'registration/subjects.php';
$new_house_url = $path_to_user_domain.'registration/house.php';
$new_grade_level_url = $path_to_user_domain.'registration/grade-levels.php';
$new_block_url = $path_to_user_domain.'registration/blocks.php';
$new_class_url = $path_to_user_domain.'registration/classes.php';

//Accounts
$acc_staff_list_url = $path_to_user_domain.'accounts/staff-list.php';

//Subjects folder
$path_to_subjects = $path_to_user_domain.'subjects/';



// Learning Resources
$new_learning_resources_notes_url = $path_to_subjects.'learning-resources/subject-notes.php';

//profile
$profile_url = $path_to_user_domain."settings/profile.php";
// Sign Out
$logout_url = $base_url."includes/sign-out.php";


//Get the users information
$get_user_info = $conn->prepare('SELECT * FROM  user_information  WHERE user_id = ?');
$get_user_info->bind_param('s',$UserID);
$get_user_info->execute();
$user_info = $get_user_info->get_result()->fetch_assoc();

$GetStudentsActiveSchool = $conn->prepare('SELECT * FROM  school_students  WHERE student_id = ? and status = "Active"');
$GetStudentsActiveSchool->bind_param('s',$UserID);
$GetStudentsActiveSchool->execute();
$GetStudentsActiveSchoolResults = $GetStudentsActiveSchool->get_result();

if ($GetStudentsActiveSchoolResults->num_rows > 0) {
    while ($row = $GetStudentsActiveSchoolResults->fetch_assoc()) {
        $center_no = $row['center_no'];
        $get_school_info = $conn->prepare('SELECT * FROM  school_info  WHERE center_no = ?');
        $get_school_info->bind_param('s',$center_no);
        $get_school_info->execute();
        $school_info = $get_school_info->get_result()->fetch_assoc();
    }
}



function getMyClass($conn, $UserID) {
    $get_class = $conn->prepare("SELECT * FROM classes WHERE class_id = (SELECT class_id FROM student_class  WHERE student_id = ?)");
    $get_class->bind_param("s", $UserID);
    $get_class->execute();
    $get_class_results = $get_class->get_result();
    
    if ($get_class_results->num_rows > 0) {
        $class_info = array();
        while ($row = $get_class_results->fetch_assoc()) {
            $class_info[] = $row;
        }
        return $class_info;
    } else {
        return array(
            "message" => "You are currently not registered under any class please contact your school to resolve this issue.",
            "value" => null
        );
    }
}
function getMySubjects($conn, $center_no, $UserID) {
    // First get all subjects offered by the school
    $get_school_subjects = $conn->prepare("SELECT subject_code FROM school_subjects WHERE center_no = ?");
    $get_school_subjects->bind_param("s", $center_no);
    $get_school_subjects->execute();
    $school_subjects_result = $get_school_subjects->get_result();
    
    $mysubjects = array();
    
    if ($school_subjects_result->num_rows > 0) {
        while ($row = $school_subjects_result->fetch_assoc()) {
            // Check if this subject is registered by the student
            $check_student_subject = $conn->prepare("
                SELECT s.* FROM subjects s JOIN student_subjects ss ON s.subject_code = ss.subject_code
                WHERE ss.subject_code = ? AND ss.student_id = ?
            ");
            $check_student_subject->bind_param("ss", $row['subject_code'], $UserID);
            $check_student_subject->execute();
            $student_subject_result = $check_student_subject->get_result();
            
            if ($student_subject_result->num_rows > 0) {
                while ($subj_row = $student_subject_result->fetch_assoc()) {
                    $mysubjects[] = $subj_row;
                }
            }
        }
        if (empty($mysubjects)) {
            return array(
                "message" => "No registered subjects found",
                "value" => null
            );
        }
        return $mysubjects;
    } else {
        return array(
            "message" => "No subjects offered at this center",
            "value" => null
        );
    }
}


function getContacts($conn, $UserID) {
    $get_contacts= $conn->prepare("SELECT * FROM user_contacts WHERE user_id = ?");
    $get_contacts->bind_param("s", $UserID);
    $get_contacts->execute();
    $get_contacts_results = $get_contacts->get_result();
    
    if ($get_contacts_results->num_rows > 0) {
        $contacts = array();
        while ($row = $get_contacts_results->fetch_assoc()) {
            $contacts[] = $row;
        }
        return $contacts;
    } else {
        return array(
            "message" => "No Contacts have been registered.",
            "value" => null
        );
    }
}
function getSchoolRooms($conn, $center_no,$exists) {
    $get_rooms = $conn->prepare("SELECT * FROM  school_rooms WHERE house_no ". $exists . "(SELECT house_no FROM school_houses  WHERE center_no = ?)");
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

