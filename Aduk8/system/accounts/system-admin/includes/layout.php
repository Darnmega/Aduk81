<?php require_once("authenticate.php");
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
                <h1 id="typing-heading"> Welcome to Aduk8</h1>


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
                            <i class="fas fa-user"></i>
                            <span class="menu-text">Registration</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="<?= $new_school_url; ?>">Schools</a></li>
                            <li><a href="<?= $new_system_admin_url; ?>">System Administrators</a></li>
                            <li><a href="<?= $new_subject_url; ?>">Subjects</a></li>
                            <li><a href="<?= $new_grade_level_url; ?>">Grade Levels</a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-users"></i>
                            <span class="menu-text">Accounts</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">Schools</a></li>
                            <li><a href="#">System Administrators</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">
                            <i class="fas fa-chart-line"></i>
                            <span class="menu-text">Analytics</span>
                        </a>
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
                    <img src="<?= $base_dir_assets . 'img/' . ($user_info['profile_img'] ?? 'default.png'); ?>" alt="User Profile" class="profile-img">
                    <div class="user-info">
                        <span class="username"><?= htmlspecialchars($user_info['prefix'].' '.$user_info['first_name'][0].'. '.$user_info['last_name'])?></span>
                        <span class="user-position">Administrator</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script src="<?= $base_dir_assets; ?>js/main.js"></script>
</body>

</html>