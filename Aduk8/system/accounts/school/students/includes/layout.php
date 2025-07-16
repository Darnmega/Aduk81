<?php require_once("authenticate.php"); 
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
                            <i class="fas  fa-newspaper"></i>
                            <span class="menu-text">Notice Board</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">School Events</a></li>
                            <li><a href="#">School Notices</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">
                            <i class="fas fa-wallet"></i>
                            <span class="menu-text">School Fees</span>
                        </a>
                    </li>
                    <li class="menu-item has-submenu">
                        <a href="#">
                            <i class="fas fa-chart-line"></i>
                            <span class="menu-text">Academic Performance</span>
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="#">My Performance</a></li>
                            <li><a href="#">Classes</a></li>
                            <li><a href="#">Houses</a></li>
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
                            <li><a href="<?= $profile_url;?>">Profile</a></li>
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