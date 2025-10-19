<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Get current page from URL parameter, default to dashboard
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Validate page to prevent directory traversal
$allowed_pages = ['dashboard', 'comp', 'analytics', 'gaps', 'met', 'summary', 'succession', 'career'];
if (!in_array($current_page, $allowed_pages)) {
    $current_page = 'dashboard';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPA Competency Monitoring System</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    min-height: 100vh;
}

        /* Sidebar Navigation */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            color: #2c3e50;
            padding: 20px 0;
            padding-bottom: 100px;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e3f2fd;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 20px;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #e3f2fd;
            margin-bottom: 20px;
        }

        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px auto;
            border: 3px solid #42a5f5;
            box-shadow: 0 4px 15px rgba(66, 165, 245, 0.3);
            display: block;
        }

        .sidebar-header h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: #1976d2;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            color: #64b5f6;
        }

        .sidebar-header h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: #1976d2;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            color: #64b5f6;
        }

        .nav-section {
            margin-bottom: 25px;
        }

        .nav-section-title {
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            color: #90caf9;
        }

        .nav-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #546e7a;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
            position: relative;
            margin: 2px 10px;
            border-radius: 10px;
        }

        .nav-item:hover {
            background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
            border-left-color: #42a5f5;
            color: #1976d2;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, #bbdefb 0%, #90caf9 100%);
            border-left-color: #1976d2;
            color: #0d47a1;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(66, 165, 245, 0.3);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .logout-btn {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 280px;
            padding: 18px 20px;
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
            border-top: 2px solid #ffcdd2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            z-index: 1001;
            font-weight: 600;
            box-shadow: 0 -4px 15px rgba(239, 83, 80, 0.3);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            transform: translateY(-2px);
            box-shadow: 0 -6px 20px rgba(239, 83, 80, 0.4);
        }

        .logout-btn i {
            font-size: 1.1rem;
        }

        /* Main Content */
  .main-content {
    margin-left: 280px;
    padding: 20px;
    min-height: 100vh;
    background: #f5f5f5ff;     
    width: calc(100% - 280px); 
}

       .content-header {
    padding: 25px 30px;
    border-radius: 20px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 2px solid #e3f2fd;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    background: transparent; /* <--- REMOVE BOX BACKGROUND */
}

        .content-header h1 {
            font-family: 'Cinzel', serif;
            color: #1976d2;
            font-size: 2rem;
            font-weight: 600;
        }

        .content-body {
    border-radius: 20px;
    padding: 30px;
    min-height: calc(100vh - 180px);
    border: 2px solid #e3f2fd;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    background: transparent; /* <--- REMOVE BOX BACKGROUND */
}

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1002;
            background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(66, 165, 245, 0.4);
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(66, 165, 245, 0.6);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .logout-btn {
                width: 280px;
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            border: 4px solid #e3f2fd;
            border-top: 4px solid #42a5f5;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-content::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: #90caf9;
            border-radius: 3px;
        }

        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: #64b5f6;
        }

        /* Date badge styling */
        .date-badge {
            color: #1976d2;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #e3f2fd;
            border-radius: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div>
            <div class="spinner"></div>
            <p style="margin-top: 15px; color: #42a5f5; font-weight: 500; text-align: center;">Loading...</p>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-header">
                <img src="logo.png" alt="PPA Logo" onerror="this.style.display='none'">
                <h2>PPA System</h2>
                <p>Competency Monitoring</p>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Main Menu</div>
                <a href="combine.php?page=dashboard" class="nav-item <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="combine.php?page=comp" class="nav-item <?php echo $current_page == 'comp' ? 'active' : ''; ?>">
                    <i class="fas fa-search"></i>
                    <span>Employee Search</span>
                </a>
                <a href="combine.php?page=analytics" class="nav-item <?php echo $current_page == 'analytics' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Competency Management</div>
                <a href="combine.php?page=gaps" class="nav-item <?php echo $current_page == 'gaps' ? 'active' : ''; ?>">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Competency Gaps</span>
                </a>
                <a href="combine.php?page=met" class="nav-item <?php echo $current_page == 'met' ? 'active' : ''; ?>">
                    <i class="fas fa-check-circle"></i>
                    <span>Met Competencies</span>
                </a>
                <a href="combine.php?page=summary" class="nav-item <?php echo $current_page == 'summary' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Summary Report</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Career Development</div>
                <a href="combine.php?page=succession" class="nav-item <?php echo $current_page == 'succession' ? 'active' : ''; ?>">
                    <i class="fas fa-users-cog"></i>
                    <span>Succession Development</span>
                </a>
                <a href="combine.php?page=career" class="nav-item <?php echo $current_page == 'career' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Career Progression</span>
                </a>
            </div>
        </div>

        <a href="logout3.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="content-header">
            <h1><?php 
                $page_titles = [
                    'dashboard' => 'Dashboard',
                    'comp' => 'Employee Search',
                    'analytics' => 'Analytics Dashboard',
                    'gaps' => 'Competency Gaps',
                    'met' => 'Met Competencies',
                    'summary' => 'Summary Report',
                    'succession' => 'Succession Development',
                    'career' => 'Career Progression'
                ];
                echo isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Dashboard';
            ?></h1>
            <div class="date-badge">
                <i class="far fa-clock"></i>
                <?php echo date('F d, Y'); ?>
            </div>
        </div>

        <div class="content-body">
            <div id="page-content">
                <?php
                // Include the appropriate page based on the parameter
                $page_files = [
                    'dashboard' => 'lnd.php',
                    'comp' => 'comp.php',
                    'analytics' => 'bridge.php',
                    'gaps' => 'gaps.php',
                    'met' => 'met.php',
                    'summary' => 'sum.php',
                    'succession' => 'succ.php',
                    'career' => 'cpp.php'
                ];

                if (isset($page_files[$current_page]) && file_exists($page_files[$current_page])) {
                    include $page_files[$current_page];
                } else {
                    echo '<div style="text-align: center; padding: 60px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: #42a5f5; margin-bottom: 20px;"></i>
                            <h2 style="color: #1976d2;">Page Not Found</h2>
                            <p style="color: #64b5f6;">The requested page does not exist.</p>
                          </div>';
                }
                ?>
            </div>
        </div>
    </main>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Show loading overlay when clicking nav items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Show loading overlay
                document.getElementById('loadingOverlay').classList.add('active');
            });
        });

        // Hide loading overlay when page loads
        window.addEventListener('load', function() {
            document.getElementById('loadingOverlay').classList.remove('active');
        });
    </script>
</body>
</html>