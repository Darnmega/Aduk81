<?php
require_once('../../includes/authenticate.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'GetClassUsingClassGrade':
            if (isset($_POST['grade'])) {
                $grade = $_POST['grade'];
                $classes = getClassesByGrade($conn, $grade, $center_no);
                header('Content-Type: application/json');
                echo json_encode($classes);
                exit;
            }
            break;
            
        case 'GetStudentsUsingStartingGrade':
            if (isset($_POST['grade'])) {
                $grade = $_POST['grade'];
                $students = getStudentsByStartingGrade($conn, $grade, $center_no);
                header('Content-Type: application/json');
                echo json_encode($students);
                exit;
            }
            break;
    }
}

function getClassesByGrade($conn, $grade, $center_no) {

    $query = "SELECT c.class_id, c.range_no, r.name 
              FROM classes c
              JOIN house_class_range r ON c.range_no = r.range_no
              WHERE c.grade_id = ? 
              AND c.center_no = ?
              ORDER BY r.name";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $grade, $center_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    
    return !empty($classes) ? $classes : ['message' => 'No classes found for this grade'];
}

function getStudentsByStartingGrade($conn, $grade, $center_no) {
    $query = "SELECT *  FROM user_information WHERE user_id IN ( SELECT student_id FROM school_students WHERE grade_no = ? AND center_no = ?)
";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $grade, $center_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    return !empty($students) ? $students : ['message' => 'No unassigned students found for this grade'];
}