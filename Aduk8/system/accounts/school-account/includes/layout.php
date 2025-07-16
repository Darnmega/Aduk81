<?php 
require_once("authenticate.php");
include('urls.php');

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
                    <h1 id="typing-heading"> Welcome to <?= $schoolInformation['school_name'].' '.$schoolInformation['school_classification'] ?></h1>
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
                        <a href="<?= htmlspecialchars($dashboardFile); ?>">
                            <i class="fas fa-home"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas  fa-newspaper"></i>
                            <span class="menu-text">Notice Board</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">School Events</a></li>
                            <li><a href="#">School Notices</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-user"></i>
                            <span class="menu-text">Registration</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= htmlspecialchars($registerStudentFile); ?>">Students</a></li>
                            <li><a href="<?= htmlspecialchars($registerGuardiansFile); ?>">Guardians</a></li>
                            <li><a href="<?= htmlspecialchars($registerStaffFile); ?>">Staff</a></li>
                            <li><a href="<?= htmlspecialchars($registerSubjectFile); ?>">Subjects</a></li>
                            <li><a href="<?= htmlspecialchars($registerGradeLevelFile); ?>">Grade Levels</a></li>
                            <li><a href="<?= htmlspecialchars($registerBlockFile);?>">School Blocks</a></li>
                            <li><a href="<?= htmlspecialchars($registerHouseFile);?>">House</a></li>
                            <li><a href="<?= htmlspecialchars($registerClassFile);?>">Classes</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-users"></i>
                            <span class="menu-text">Accounts</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Students</a></li>
                            <li><a href="#">Guardians</a></li>
                            <li><a href="<?= htmlspecialchars($accountStaffListFile);?>">Staff</a></li>
                            <li><a href="#">Subjects</a></li>
                            <li><a href="#">Grade Levels</a></li>
                            <li><a href="#">School Blocks</a></li>
                            <li><a href="#">House</a></li>
                            <li><a href="#">Classes</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="<?= htmlspecialchars($new_learning_resources_url); ?>">
                            <i class="fas fa-wallet"></i>
                            <span class="menu-text">School Fees</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= $new_learning_resources_url; ?>">
                            <i class="fas fa-folder"></i>
                            <span class="menu-text">Learning Resources</span>
                        </a>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-sitemap"></i>
                            <span class="menu-text">Class Allocation</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">                             
                            <li><a href="<?= htmlspecialchars($studentsAllocationFile);?>">Students</a></li>
                            <li><a href="<?= htmlspecialchars($teacherAllocationFile);?>">Teachers</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-chart-line"></i>
                            <span class="menu-text">Academic Performance</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Student</a></li>
                            <li><a href="#">Teacher's</a></li>
                            <li><a href="#">Classes</a></li>
                            <li><a href="#">Houses </a></li>
                            <li><a href="#">Schools</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-cog"></i>
                            <span class="menu-text">Settings</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= htmlspecialchars($userProfileFile);?>">Profile</a></li>
                            <li><a href="<?= htmlspecialchars($logoutUrlFile); ?>">Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <img src="<?= htmlspecialchars($profile_pic_url . ($schoolInformation['school_emblem'] ?? 'default.png')); ?>" alt="User Profile" class="profile-img">
                    <div class="user-info">
                        <span class="username"><?= htmlspecialchars($schoolInformation['school_name']);?></span>
                        <span class="user-position">School Account</span>
                    </div>

                </div>
            </div>
        </aside>
    </div>

    <script src="<?= $base_dir_assets; ?>js/main.js"></script>
</body>

</html>