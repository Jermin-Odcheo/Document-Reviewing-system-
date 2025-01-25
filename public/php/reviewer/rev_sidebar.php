<?php $current_page = basename($_SERVER['PHP_SELF'], ".php");?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SLU Dashboard</title>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/styles/sidebar.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="user-greeting">
                <p>Hello $user</p>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-section">
                    <span class="menu-header">MAIN MENU</span>

                    <a href="./rev_dashboard.php" class="menu-item <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="./rev_documents.php" class="menu-item <?php echo ($current_page == 'documents') ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>Documents</span>
                    </a>

                    <a href="./rev_status.php" class="menu-item <?php echo ($current_page == 'status') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Reviews and Status</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Your main content goes here -->
    </div>

    <script>
        // JavaScript to toggle both sidebar and overlay
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');

        function toggleSidebar() {
            const isActive = sidebar.classList.contains('active');
            if (isActive) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.add('active');
                overlay.classList.add('active');
            }
        }

        // Attach toggle to buttons and overlay
        document.querySelector('.sidebar-toggle').addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar); // Close sidebar when overlay is clicked
    </script>
</body>

</html>