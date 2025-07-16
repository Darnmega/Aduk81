<?php require_once("includes/layout.php");
$schoolSubjects = getSchoolSubjects($conn, $center_no,' NOT IN ','1');
$schoolGrades = getGradeLevels($conn, $center_no,' IN ','1');
$schoolContacts = getSchoolContacts($conn, $center_no);
$schoolBlockClassrooms = getAllSchoolRooms($conn, $center_no,' IN ');
?>

<head>
    <title>Aduk8 | Dashboard</title>
</head>
<body>
    <div class="container">
        <main class="main-content">
        <?php if(!isset($schoolInformation['school_emblem']) || empty($schoolInformation['school_emblem']) || $schoolInformation['school_emblem'] === 'default.png'):?>
                <a href="<?= htmlspecialchars($userProfileFile); ?>"><div id="responseMessage" class='error'>Your School emblem is not set, click to add</div></a>
            <?php endif; ?>
            <?php if(isset($schoolSubjects['message'])):?>
                <a href="<?= htmlspecialchars($registerSubjectFile); ?>"><div id="responseMessage" class='error'>No Subjects registered, click to add</div></a>
            <?php endif; ?>
            <?php if(isset($schoolGrades['message'])):?>
                <a href="<?= htmlspecialchars($registerGradeLevelFile); ?>"><div id="responseMessage" class='error'>No Grade levels registered, click to add</div></a>
            <?php endif; ?>
            <?php if(isset($schoolContacts['message'])):?>
                <a href="<?= htmlspecialchars($userProfileFile); ?>"><div id="responseMessage" class='error'><?= htmlspecialchars($schoolContacts['message']); ?></div></a>
            <?php endif; ?>
            <?php if(isset($schoolBlockClassrooms['message'])):?>
                <a href="<?= htmlspecialchars($registerBlockFile); ?>"><div id="responseMessage" class='error'>No blocks/Classrooms found, click to add</div></a>
            <?php endif; ?>
            <div class="content">
                <h2>Main Content</h2>
                <p>This is the main content area that adjusts when the sidebar expands/collapses.</p>
            </div>
        </main>
    </div>
</body>
</html>