<?php 
$baseDir = $_SERVER['DOCUMENT_ROOT'];
require_once("$baseDir/system/accounts/school/students/includes/authenticate.php"); 

if(isset($_GET['subj']) && !empty($_GET['subj'])){
    $subject_code = $_GET['subj'];

    $check_subject = $conn->prepare("SELECT * FROM student_subjects WHERE subject_code = ? AND student_id = ?");
    $check_subject->bind_param("ss", $subject_code,$UserID);
    $check_subject->execute();
    $check_subject_results = $check_subject->get_result();
    if($check_subject_results->num_rows <= 0 ){
        kickout($dashboard_url,  'Unable to validate subject');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $base_dir_assets; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div>
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            <?php 
            if (isset($school_info['school_name']))
            {
                echo "<h1 id='typing-heading'>Welcome To ".$school_info['school_name'] ." ".$school_info['classification'] ."</h1>";
                $center_no = $school_info['center_no'];
            } 
            else 
            { 
                echo "<h1> You Are Currently Not Active in Any School</h1>";
            }
            ?>            
            </div>
            <!-- <div class="header-right">
                <div class="search-box">
                    <input type="text" placeholder="Search...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="notification">
                    <i class="fas fa-bell"></i>
                </div>
            </div> -->
        </header>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Aduk8 Menu</h2>
            </div>

            <nav class="sidebar-menu">
                <ul>
                    <li class="menu-item">
                        <a href="<?= $dashboard_url; ?>">
                            <i class="fas fa-home"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-folder"></i>
                            <span class="menu-text">Learning Resources</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= $new_learning_resources_notes_url.'?subj='. $subject_code?>">Notes</a></li>
                            <li><a href="#">Assignments</a></li>
                            <li><a href="#">Extra Material</a></li>
                            <li><a href="#">Dictionary</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-cog"></i>
                            <span class="menu-text">Settings</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Profile</a></li>
                            <li><a href="<?= $logout_url; ?>">Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <img src="<?= $profile_pic_url . ($user_info['profile_img'] ?? 'default.png'); ?>" alt="User Profile" class="profile-img">
                    <div class="user-info">
                        <span class="username"><?= htmlspecialchars($user_info['first_name'][0].'. '.$user_info['last_name'])?></span>
                        <span class="user-position">Student</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script src="<?= $base_dir_assets; ?>js/main.js"></script>
</body>

</html>