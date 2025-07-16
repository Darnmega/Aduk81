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

if($data['user'] == 'student')
{
    registerStudent($conn, $data,$center_no);
}else if($data['user'] == 'staff')
{
    registerStaff($conn, $data,$center_no);
}
else if($data['user'] == 'subject_selection')
{
    submitSubjects($conn , $data);
}
else if($data['user'] == 'grade_level')
{
    submitGradeLlevel($conn , $data);
}
else
{
    response(false, 'invalid request ');
}
// clean all responses before sending
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
// handles all responses
function response($status, $message, $url = null, $errors = []) {
    http_response_code($status ? 200 : 400);
    echo json_encode([
        'status' => $status ? 'success' : 'error',
        'message' => $message,
        'url' => $url,
        'errors' => $errors
    ]);
    exit;
}
//
function checkUsername($conn, $username){
    $check = $conn->prepare("SELECT * FROM user_credentials WHERE username = ?");
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
// check if there is a user having the same ID
function checkUsersID($conn, $ID){
    $check = $conn->prepare("SELECT * FROM user_information WHERE id_number = ?");
    $check->bind_param("s", $ID);
    if (!$check->execute()) {
        throw new Exception("Failed to check ID number: " . $check->error);
    }
    
    $check->store_result();
    if ($check->num_rows > 0) 
    {
        cleanOutput();
        response(false, 'Field Error', null, [
            'fields' => ['id_number'],
            'message' => 'User`s ID/ Passport/ Birth Certificate Already exists'
        ]);
    }
    $check->close();
}

// creates unique filenames
function createuniquefilename($conn) {
    $duplicate = true;

    while($duplicate) {
        $filename = bin2hex(random_bytes(10)); // 20-character hex string
        $full_filename = $filename . ".pdf";
    
        $checkDuplicate = $conn->prepare('SELECT * FROM  users_documents  WHERE  doc_id  = ?');
        $checkDuplicate->bind_param('s', $full_filename);
        $checkDuplicate->execute();
        $checkDuplicate_results = $checkDuplicate->get_result();
        if($checkDuplicate_results->num_rows <= 0) {
            $duplicate = false;
            return $full_filename;
        }
    }
}
// uploading id documentation
function upload_id_doc($conn, $data, $base_dir_userfile_uploads) {
    // Normalize the directory path
    $base_dir_userfile_uploads = rtrim($base_dir_userfile_uploads, '/') . '/';
    $full_upload_path = $_SERVER['DOCUMENT_ROOT'] . $base_dir_userfile_uploads;
    
    // Debug: Log the upload path
    error_log("Attempting to upload to: " . $full_upload_path);

    // Check and create directory if needed
    if (!file_exists($full_upload_path)) {
        if (!mkdir($full_upload_path, 0755, true)) {
            error_log("Failed to create directory: " . $full_upload_path);
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['id_doc'],
                'message' => 'Failed to create upload directory'
            ]);
        }
    }

    // Verify directory is writable
    if (!is_writable($full_upload_path)) {
        error_log("Directory not writable: " . $full_upload_path);
        cleanOutput();
        response(false, 'Document Error', null, [
            'fields' => ['id_doc'],
            'message' => 'Upload directory is not writable'
        ]);
    }

    // Check if file was provided
    if (!isset($data['id_doc']) || empty($data['id_doc'])) {
        cleanOutput();
        response(false, 'Document Error', null, [
            'fields' => ['id_doc'],
            'message' => 'No ID document provided'
        ]);
    }

    $fileData = $data['id_doc'];
    
    // Check if it's base64 encoded
    if (preg_match('/^data:application\/(pdf);base64,/', $fileData, $matches)) {
        $base64Data = substr($fileData, strpos($fileData, ',') + 1);
        $fileContent = base64_decode($base64Data);
        
        if ($fileContent === false) {
            error_log("Base64 decode failed");
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['id_doc'],
                'message' => 'Failed to decode file'
            ]);
        }
        
        // Verify PDF header
        if (strpos($fileContent, '%PDF') !== 0) {
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['id_doc'],
                'message' => 'Invalid PDF file'
            ]);
        }
        
        // Generate unique filename
        $filename = createuniquefilename($conn);
        $filePath = $full_upload_path . $filename;
        
        // Save the file
        $bytesWritten = file_put_contents($filePath, $fileContent);
        
        if ($bytesWritten === false || $bytesWritten === 0) {
            error_log("Failed to write file to: " . $filePath);
            error_log("Error: " . print_r(error_get_last(), true));
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['id_doc'],
                'message' => 'Failed to save file'
            ]);
        }
        
        // Verify file was created
        if (!file_exists($filePath)) {
            error_log("File verification failed for: " . $filePath);
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['id_doc'],
                'message' => 'File upload verification failed'
            ]);
        }
        
        // Return relative path for database storage
        return $filename;
    } else {
        cleanOutput();
        response(false, 'Document Error', null, [
            'fields' => ['id_doc'],
            'message' => 'Invalid file format'
        ]);
    }
}

    // this function registers all users qualification documents
function upload_qualification_doc($conn, $data, $base_dir_userfile_uploads) {
    // Normalize the directory path
    $base_dir_userfile_uploads = rtrim($base_dir_userfile_uploads, '/') . '/';
    $full_upload_path = $_SERVER['DOCUMENT_ROOT'] . $base_dir_userfile_uploads;
    
    // Debug: Log the upload path
    error_log("Attempting to upload qualification doc to: " . $full_upload_path);

    // Check and create directory if needed
    if (!file_exists($full_upload_path)) {
        if (!mkdir($full_upload_path, 0755, true)) {
            error_log("Failed to create directory: " . $full_upload_path);
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['qualification_doc'],
                'message' => 'Failed to create upload directory'
            ]);
        }
    }

    // Verify directory is writable
    if (!is_writable($full_upload_path)) {
        error_log("Directory not writable: " . $full_upload_path);
        cleanOutput();
        response(false, 'Document Error', null, [
            'fields' => ['qualification_doc'],
            'message' => 'Upload directory is not writable'
        ]);
    }

    // Check if file was provided
    if (!isset($data['qualification_doc']) || empty($data['qualification_doc'])) {
        cleanOutput();
        response(false, 'Document Error', null, [
            'fields' => ['qualification_doc'],
            'message' => 'No qualification document provided'
        ]);
    }

    $fileData = $data['qualification_doc'];
    
    // Check if it's base64 encoded
    if (preg_match('/^data:application\/(pdf);base64,/', $fileData, $matches)) {
        $base64Data = substr($fileData, strpos($fileData, ',') + 1);
        $fileContent = base64_decode($base64Data);
        
        if ($fileContent === false) {
            error_log("Base64 decode failed for qualification doc");
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['qualification_doc'],
                'message' => 'Failed to decode file'
            ]);
        }
        
        // Verify PDF header
        if (strpos($fileContent, '%PDF') !== 0) {
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['qualification_doc'],
                'message' => 'Invalid PDF file'
            ]);
        }
        
        // Generate unique filename
        $filename = createuniquefilename($conn);
        $filePath = $full_upload_path . $filename;
        
        // Save the file
        $bytesWritten = file_put_contents($filePath, $fileContent);
        
        if ($bytesWritten === false || $bytesWritten === 0) {
            error_log("Failed to write qualification file to: " . $filePath);
            error_log("Error: " . print_r(error_get_last(), true));
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['qualification_doc'],
                'message' => 'Failed to save file'
            ]);
        }
        
        // Verify file was created
        if (!file_exists($filePath)) {
            error_log("Qualification file verification failed for: " . $filePath);
            cleanOutput();
            response(false, 'Document Error', null, [
                'fields' => ['qualification_doc'],
                'message' => 'File upload verification failed'
            ]);
        }
        
        // Return relative path for database storage
        return $filename;
    } else {
        cleanOutput();
        response(false, 'Document Error', null, [
            'fields' => ['qualification_doc'],
            'message' => 'Invalid file format'
        ]);
    }
}
// this function creates all the user's IDs
function createId($conn, $center_no, $table,$field, $Id_prefix,$id_length) {
    // Validate table name to prevent SQL injection
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        throw new Exception('Invalid table name');
    }

    $currentYear = date('Y');
    $get_users = $conn->prepare('SELECT COUNT('.$field.') as users FROM '.$table.' WHERE center_no = ? AND YEAR(start_date) = ?');
    if (!$get_users) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $get_users->bind_param('ss', $center_no, $currentYear);
    if (!$get_users->execute()) {
        throw new Exception('Execute failed: ' . $get_users->error);
    }
    
    $result = $get_users->get_result();
    $users = $result->fetch_assoc();
    $get_users->close();
    
    $id_no = $users['users'] + 1;
            
    $duplicate = true;
    $new_id = '';

    while($duplicate) {
        $padded_id = str_pad($id_no, $id_length, '0', STR_PAD_LEFT);
        $new_id = $Id_prefix . $padded_id . $currentYear;
        
        $check_duplicate = $conn->prepare('SELECT * FROM '.$table.' WHERE '. $field.' = ?');
        if (!$check_duplicate) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $check_duplicate->bind_param('s', $new_id);
        if (!$check_duplicate->execute()) {
            throw new Exception('Execute failed: ' . $check_duplicate->error);
        }
        
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
// this function registeres all students
function registerStudent($conn, $data, $center_no, )
{
    function registerCoreSubjects($conn, $center_no,$user_id){
        $get_core_subjects = $conn->prepare('SELECT * FROM subjects WHERE classification = "Core" AND subject_code IN(SELECT subject_code FROM school_subject WHERE center_no = ?)');
        $get_core_subjects->bind_param('s', $center_no);
        $get_core_subjects->execute();
        $result = $get_core_subjects->get_result();

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $register_subjects = $conn->prepare('INSERT INTO `student_subjects`(`student_id`, `subject_code`, `_on`, `_by`) VALUES (? ,? ,NOW() ,? )');
                $register_subjects->bind_param('sss',  $user_id, $row['subject_code'],$center_no   );
                $register_subjects->execute();
            }
        }
    }

    try {  
        // Assign variables
        $table = 'school_students';
        $field = 'student_id';
        $id_length = 5;
        $id_prefix = 'STD';
        $residence = ucwords(strtolower($data['residence']));
        $first_name = ucwords(strtolower($data['first_name']));
        $middle_name = ucwords(strtolower($data['middle_name']));
        $last_name = ucwords(strtolower($data['last_name']));
        $id_number = strtoupper($data['id_number']);
        $username = $id_number;
        $role = "Student";
        $passhint ='Your Password is your last name and your ID / passport/ Birthcertificate number no space e.g Molefe003923';

        // duplication checks must check before everything
        checkUsername($conn, $username);
        checkUsersID($conn, $id_number);

        // generate a new user_id
        $user_id = createId($conn, $center_no, $table, $field,$id_prefix,$id_length);

        // Hash password
        $password = $last_name.$id_number;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Start transaction
        $conn->begin_transaction();
        $id_doc = upload_id_doc($conn, $data,'/system/accounts/uploads/files/usr-docs/students/docs/');
        $qual_doc = upload_qualification_doc($conn, $data,'/system/accounts/uploads/files/usr-docs/students/docs/');

        try {
            registerCoreSubjects($conn, $center_no, $user_id);
            // Prepare all statements
            $insert_students_table = $conn->prepare("INSERT INTO `school_students`(`student_id`, `center_no`, `qualification_doc`, `start_date`,  	grade_no ,`status`, `_on`)
                                                     VALUES ( ?, ?, ?, ?, ?, 'Pending', NOW())");
            $insert_user_info = $conn->prepare("INSERT INTO user_information (`user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `nationality`, `id_number`, `id_document`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_user_contacts = $conn->prepare("INSERT INTO user_contacts (user_id, phone, email, physical, postal, place_of_residence) 
                VALUES (?, ?, ?, ?, ?, ?)"); 
            $insert_id_doc =$conn->prepare("INSERT INTO `users_documents`(`doc_id`, `user_id`, `doc_type`, `doc_name`, `_on`, `_by`) 
                VALUES ( ?, ?, 'ID Document', ?, NOW(), ?)"); 
            $insert_qual_doc =$conn->prepare("INSERT INTO `users_documents`(`doc_id`, `user_id`, `doc_type`, `doc_name`, `_on`, `_by`)
                VALUES ( ?, ?, 'Qualification', ?, NOW(), ?)");   
            $insert_user_creds = $conn->prepare("INSERT INTO user_credentials (user_id, username, password, hint, role,acc_status) 
                VALUES (?, ?, ?, ?, ?,'Pending')");

            // Check for prepare errors
            $errors = [];
            if (!$insert_students_table) $errors[] = "system_admins prepare failed: " . $conn->error;
            if (!$insert_user_info) $errors[] = "user_information prepare failed: " . $conn->error;
            if (!$insert_user_contacts) $errors[] = "user_contacts prepare failed: " . $conn->error;
            if (!$insert_id_doc) $errors[] = "ID documentation prepare failed: " . $conn->error;
            if (!$insert_qual_doc) $errors[] = "qualification documentation prepare failed: " . $conn->error;
            if (!$insert_user_creds) $errors[] = "user_credentials prepare failed: " . $conn->error;
            
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }

            // Bind parameters and execute
            $insert_students_table->bind_param("sssss",
                $user_id,
                $center_no, 
                $qual_doc,
                $data['start_date'],
                $data['grade_level'],
            );
            $insert_user_info->bind_param("ssssssss", 
                $user_id, 
                $first_name, 
                $middle_name,
                $last_name, 
                $data['DOB'],
                $data['nationality'],
                $id_number,
                $id_doc
                
            );
            $insert_user_contacts->bind_param("ssssss", 
                $user_id, 
                $data['phone'], 
                $data['email'] ,
                $data['physical'] , 
                $data['postal'],
                $residence
            );
            $insert_id_doc->bind_param("ssss", 
                $id_doc,
                $user_id, 
                $id_doc, 
                $center_no,
            );
            $insert_qual_doc->bind_param("ssss", 
                $qual_doc, 
                $user_id, 
                $qual_doc,
                $center_no
            );
            $insert_user_creds->bind_param("sssss", 
                $user_id, 
                $username,
                $hashedPassword,
                $passhint,
                $role
            );
            // Execute all inserts
            $results = [
                'student_school' => $insert_students_table->execute(),
                'user_information' => $insert_user_info->execute(),
                'user_contacts' => $insert_user_contacts->execute(),
                'user_id' => $insert_id_doc->execute(),
                'user_document' => $insert_qual_doc->execute(),
                'user_credentials' => $insert_user_creds->execute()
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
            $insert_students_table->close();
            $insert_user_info->close();
            $insert_user_contacts->close();
            $insert_id_doc->close();
            $insert_qual_doc->close();
            $insert_user_creds->close();
            cleanOutput();
            response(true, 'New student registration successful!', 'student.php');

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

// this function registeres all staff members
function registerStaff($conn, $data, $center_no, )
{

    try {  
        // Assign variables
        $table = 'school_staff';
        $field = 'staff_id';
        $id_length = 3;
        $id_prefix = 'STF';
        $residence = ucwords(strtolower($data['residence']));
        $first_name = ucwords(strtolower($data['first_name']));
        $middle_name = ucwords(strtolower($data['middle_name']));
        $last_name = ucwords(strtolower($data['last_name']));
        $id_number = strtoupper($data['id_number']);
        $username = $id_number;
        $passhint ='Your Password is your last name and your ID / passport/ Birthcertificate number no space e.g Molefe003923';

        // duplication checks must check before everything
        checkUsername($conn, $username);
        checkUsersID($conn, $id_number);

        // generate a new user_id
        $user_id = createId($conn, $center_no, $table, $field,$id_prefix,$id_length);

        // Hash password
        $password = $last_name.$id_number;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Start transaction
        $conn->begin_transaction();
        $id_doc = upload_id_doc($conn, $data,'/system/accounts/uploads/files/usr-docs/staff/docs/');
        $qual_doc = upload_qualification_doc($conn, $data,'/system/accounts/uploads/files/usr-docs/staff/docs/');

        try {
            // Prepare all statements
            $insert_staff_table = $conn->prepare("INSERT INTO `school_staff`(`staff_id`, `center_no`, `occupation`, `qualification_doc`, `position`, `start_date`, `status`) 
                VALUES ( ?, ?, ?, ?, ?, ?, 'Pending')");
            $insert_user_info = $conn->prepare("INSERT INTO user_information (`user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `nationality`, `id_number`,marital_status, `id_document`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_user_contacts = $conn->prepare("INSERT INTO user_contacts (user_id, phone, email, physical, postal, place_of_residence) 
                VALUES (?, ?, ?, ?, ?, ?)"); 
            $insert_id_doc =$conn->prepare("INSERT INTO `users_documents`(`doc_id`, `user_id`, `doc_type`, `doc_name`, `_on`, `_by`) 
                VALUES ( ?, ?, 'ID Document', ?, NOW(), ?)"); 
            $insert_qual_doc =$conn->prepare("INSERT INTO `users_documents`(`doc_id`, `user_id`, `doc_type`, `doc_name`, `_on`, `_by`)
                VALUES ( ?, ?, 'Qualification', ?, NOW(), ?)");   
            $insert_user_creds = $conn->prepare("INSERT INTO user_credentials (user_id, username, password, hint, role,acc_status) 
                VALUES (?, ?, ?, ?, ?,'Active')");

            // Check for prepare errors
            $errors = [];
            if (!$insert_staff_table) $errors[] = "system_admins prepare failed: " . $conn->error;
            if (!$insert_user_info) $errors[] = "user_information prepare failed: " . $conn->error;
            if (!$insert_user_contacts) $errors[] = "user_contacts prepare failed: " . $conn->error;
            if (!$insert_id_doc) $errors[] = "ID documentation prepare failed: " . $conn->error;
            if (!$insert_qual_doc) $errors[] = "qualification documentation prepare failed: " . $conn->error;
            if (!$insert_user_creds) $errors[] = "user_credentials prepare failed: " . $conn->error;
            
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }

            // Bind parameters and execute
            $insert_staff_table->bind_param("ssssss",
                $user_id,
                $center_no, 
                $data['occupation'],
                $qual_doc,
                $data['position'],
                $data['empl_date'],
            );
            $insert_user_info->bind_param("sssssssss", 
                $user_id, 
                $first_name, 
                $middle_name,
                $last_name, 
                $data['DOB'],
                $data['nationality'],
                $id_number,
                $data['marital'],
                $id_doc
                
            );
            $insert_user_contacts->bind_param("ssssss", 
                $user_id, 
                $data['phone'], 
                $data['email'] ,
                $data['physical'] , 
                $data['postal'],
                $residence
            );
            $insert_id_doc->bind_param("ssss", 
                $id_doc,
                $user_id, 
                $id_doc, 
                $center_no,
            );
            $insert_qual_doc->bind_param("ssss", 
                $qual_doc, 
                $user_id, 
                $qual_doc,
                $center_no
            );
            $insert_user_creds->bind_param("sssss", 
                $user_id, 
                $username,
                $hashedPassword,
                $passhint,
                $data['occupation']

            );
            // Execute all inserts
            $results = [
                'staff_school' => $insert_staff_table->execute(),
                'user_information' => $insert_user_info->execute(),
                'user_contacts' => $insert_user_contacts->execute(),
                'user_id' => $insert_id_doc->execute(),
                'user_document' => $insert_qual_doc->execute(),
                'user_credentials' => $insert_user_creds->execute()
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
            $insert_staff_table->close();
            $insert_user_info->close();
            $insert_user_contacts->close();
            $insert_id_doc->close();
            $insert_qual_doc->close();
            $insert_user_creds->close();
            cleanOutput();
            response(true, 'New student registration successful!', 'student.php');

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
        // Check if username exists
        checkUsername($conn, $data['username']);

        $checkcenterNo = $conn->prepare("SELECT * FROM school_info WHERE center_no = ?");
        $checkcenterNo->bind_param("s", $data['center_no']);
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
            $insert_school_contacts = $conn->prepare("INSERT INTO `school_contacts`(`center_no`, `phone`, `email`, `postal_address`, `physical_address`, `website`, `location`)VALUES (?, ?, ?, ?, ?, ?, ?)");    
            $insert_school_creds = $conn->prepare("INSERT INTO user_credentials (user_id, username, password, hint, role) VALUES (?, ?, ?, ?, ?)");

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
                $data['center_no'], 
                $data['name'], 
                $data['classification'],
                $data['est_date'], 
                $data['motto'],
            );
            $insert_school_contacts->bind_param("sssssss", 
                $data['center_no'], 
                $data['phone'], 
                $data['email'] ,
                $data['postal'], 
                $data['physical'] ,
                $data['website'],
                $data['location']
            );
            $insert_school_creds->bind_param("sssss", 
                $data['center_no'], 
                $data['username'],
                $hashedPassword,
                $data['hint'],
                $role
            );

            // Execute all inserts
            $results = [
                'school_info' => $insert_school_info->execute(),
                'school_contacts' => $insert_school_contacts->execute(),
                'user_credentials' => $insert_school_creds->execute()
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
            response(true, 'School Registration successful!', '../dashboard.php');

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

// submit all the selected subjects
function submitSubjects($conn , $data) {
    $center_no = $_SESSION['user_id'];
    $selectedSubjects = $data['subjects'];
    try {
        // Begin transaction
        $conn->begin_transaction();


    
       // Insert selected subjects
       $stmt = $conn->prepare("INSERT INTO school_subjects (center_no, subject_code,_on) VALUES (?, ?,NOW())");
    
       foreach ($selectedSubjects as $subjectCode) {
           $stmt->bind_param("ss", $center_no, $subjectCode);
           $stmt->execute();
       }
        // Commit transaction
        $conn->commit();

        // Close statements
        $stmt->close();

        cleanOutput();
        response(true, 'Subjects Registered successful!', 'subjects.php');

    } catch (Exception $e) {
        $conn->rollback();
        cleanOutput();
        response(false, 'Database error: ' . $e->getMessage());
    }


}
// submit all the selected grade levels
function submitGradeLlevel($conn , $data) {
    $center_no = $_SESSION['user_id'];
    $selectedGradeLevels = $data['grade_level'];
    try {
        // Begin transaction
        $conn->begin_transaction();


    
       // Insert selected subjects
       $stmt = $conn->prepare("INSERT INTO school_grade_levels (center_no, grade_no,_on) VALUES (?, ?,NOW())");
    
       foreach ($selectedGradeLevels as $grades) {
           $stmt->bind_param("ss", $center_no, $grades);
           $stmt->execute();
       }
        // Commit transaction
        $conn->commit();

        // Close statements
        $stmt->close();

        cleanOutput();
        response(true, 'Grade Level Registered successful!', 'grade-levels.php');

    } catch (Exception $e) {
        $conn->rollback();
        cleanOutput();
        response(false, 'Database error: ' . $e->getMessage());
    }


}