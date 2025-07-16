<?php require_once("../includes/layout.php"); 
  $class_infos = getMyClass($conn, $UserID);
if(isset($class_infos['message'])){
    kickout($dashboard_url, $class_infos['message']);
}
  foreach($class_infos as $class_info){
    $get_notes = $conn->prepare('SELECT * FROM  learning_resources WHERE (subject_code = ? OR subject_code = "All Subjects") AND (grade_id = ? OR  grade_id = "All Grades" ) AND (center_no = ? OR center_no= "All Schools")');
    $get_notes->bind_param('sss', $subject_code, $class_info['grade_id'],$center_no);
    $get_notes->execute();
    $get_notes_results = $get_notes->get_result();
  }



?>

<head>
    <title>Aduk8 | Subject Notes</title>
   
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="content">
                <h2>Main Content</h2>
                <p>This is the main content area that adjusts when the sidebar expands/collapses.</p>
            </div>
        </main>
    </div>
</body>
</html>