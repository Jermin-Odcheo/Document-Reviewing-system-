<link href="../../assets/styles/sidebar.css" rel="stylesheet">
<div class="sidebar">
    <div class="sidebar-header">
    <nav class="sidebar-menu">
        <div class="menu-section">
            <span class="menu-header">MAIN MENU</span>
            
            <a href="./admin_dashboard.php" class="menu-item <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <a href="./admin_logs.php" class="menu-item <?php echo ($current_page == 'logs') ? 'active' : ''; ?>">
                <i class="fas fa-history"></i>
                <span>Activity Logs</span>
            </a>

            <a href="./admin_user_manager.php" class="menu-item <?php echo ($current_page == 'users') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>
        </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event for mobile menu toggle if needed
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            menuItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
        });
    });
});
</script>