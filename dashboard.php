<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome Icons -->
</head>
<body>
    <div class="container">
        <?php
        $user = [
            'name' => 'Lethu Vilakazi',
            'profile_pic' => 'profile-pic.jpg' // Your image path
        ];
        ?>

        <!-- Sidebar / Profile Section -->
        <aside class="sidebar">
            <div class="profile">
                <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture">
                <h3><?php echo $user['name']; ?></h3>
            </div>

            <!-- Search Bar -->
            <div class="sidebar-search-bar">
                <input type="text" placeholder="Search...">
            </div>

            <!-- Main Navigation -->
            <nav class="navigation">
                <ul>
                    <!-- Home Section -->
                    <li><a href="?page=home"><i class="fas fa-home"></i> Main Navigation</a></li>
                    
                    <!-- Burial Section with Collapse -->
                    <li class="collapsible">
                        <a href="#">
                            <i class="fas fa-cross"></i> Burial Application
                            <i class="fas fa-chevron-down"></i> <!-- Collapse Icon -->
                        </a>
                        <ul class="nested">
                            <li><a href="?page=new_burial">New Burial</a></li>
                            <li><a href="?page=search_burial">Search Burial</a></li>
                            <li><a href="?page=burial_browser">Burial Browser</a></li>
                        </ul>
                    </li>

                    <!-- Graves Section with Icon -->
                    <li><a href="?page=graves"><i class="fas fa-monument"></i> Graves</a></li>

                    <!-- Ownership Section with Icon -->
                    <li><a href="?page=ownership"><i class="fas fa-user-shield"></i> Ownership</a></li>

                    <!-- Maintenance Section -->
                    <li><a href="?page=maintenance"><i class="fas fa-tools"></i> Maintenance</a></li>

                    <!-- Other Sections -->
                    <li><a href="?page=gis_viewer"><i class="fas fa-map"></i> GIS Viewer</a></li>
                    <li><a href="?page=survey_data"><i class="fas fa-chart-bar"></i> Survey Data</a></li>
                    <li><a href="?page=service_type"><i class="fas fa-concierge-bell"></i> Service Type</a></li>
                    <li><a href="?page=report"><i class="fas fa-file-alt"></i> Report</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Navigation with Icons -->
            <header>
                <div class="top-bar">
                    <div class="top-right">
                        <a href="#" class="messages">
                            <i class="fas fa-envelope"></i> <!-- Message Icon -->
                        </a>
                        <a href="#" class="notifications">
                            <i class="fas fa-bell"></i> <!-- Notification Icon -->
                        </a>
                        <a href="#" class="profile-settings">
                            <i class="fas fa-user"></i> <!-- Profile Icon -->
                        </a>
                        <a href="#" class="settings">
                            <i class="fas fa-cog"></i> <!-- Settings Icon -->
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <section class="content">
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];

                    switch ($page) {
                        case 'home':
                            echo "<h1>Welcome to the Home Page</h1>";
                            echo "<p>This is the main navigation page.</p>";
                            break;
                        case 'new_burial':
                            include 'new_burial.php';
                            break;
                        case 'search_burial':
                            include 'search_burial.php';
                            break;
                        case 'burial_browser':
                            include 'burial_browser.php';
                            break;
                        case 'graves':
                            include 'graves.php';
                            break;
                        case 'ownership':
                            include 'ownership.php';
                            break;
                        case 'maintenance':
                            include 'maintenance.php';
                            break;
                        case 'gis_viewer':
                            include 'gis_viewer.php';
                            break;
                        case 'survey_data':
                            include 'survey_data.php';
                            break;
                        case 'service_type':
                            include 'service_type.php';
                            break;
                        case 'report':
                            include 'report.php';
                            break;
                        default:
                            echo "<h1>Welcome to the Dashboard</h1>";
                            echo "<p>Select a section from the sidebar to get started.</p>";
                            break;
                    }
                } else {
                    echo "<h1>Welcome to the Dashboard</h1>";
                    echo "<p>Select a section from the sidebar to get started.</p>";
                }
                ?>
            </section>
        </main>
    </div>

    <!-- JavaScript for Collapsible Menu -->
    <script>
        // Toggle collapse/expand of the submenu
        document.querySelectorAll('.collapsible > a').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                let nestedMenu = toggle.nextElementSibling;
                let icon = toggle.querySelector('.fa-chevron-down');
                
                // Toggle visibility of the submenu
                if (nestedMenu.style.display === "block") {
                    nestedMenu.style.display = "none";
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    nestedMenu.style.display = "block";
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });
    </script>
</body>
</html>
