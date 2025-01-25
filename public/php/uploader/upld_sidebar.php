<?php $current_page = basename($_SERVER['PHP_SELF'], ".php");?>
<link href="../../assets/styles/sidebar.css" rel="stylesheet">
<div class="sidebar">
    <nav class="sidebar-menu">
        <div class="menu-section">
            <span class="menu-header">MAIN MENU</span>
            
            <a href="./upld_dashboard.php" class="menu-item <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <a href="./upld_documents.php" class="menu-item <?php echo ($current_page == 'documents') ? 'active' : ''; ?>">
                <i class="fas fa-history"></i>
                <span>Documents</span>
            </a>

            <a href="./upld_status.php" class="menu-item <?php echo ($current_page == 'status') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span> Reviews and Status</span>
            </a>
        </div>
</div>

<script>
// Select the sidebar and overlay elements
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');

// Function to close the sidebar and overlay
function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
}

// Attach the click event listener to the overlay
overlay.addEventListener('click', closeSidebar);

// Optional: Toggle sidebar (e.g., with a button)
const openSidebarButton = document.querySelector('.open-sidebar');
openSidebarButton?.addEventListener('click', () => {
    sidebar.classList.add('active');
    overlay.classList.add('active');
});



</script>
