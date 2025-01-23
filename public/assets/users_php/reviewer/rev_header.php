<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../../styles/header_footer.css" rel="stylesheet">
</head>

<body>
    <header>
        <link rel="stylesheet" href="../styles/index.css">
        <nav class="navbar w-100 justify-content-between py-2 navbar-dark bg-dark">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <img src="../../img/SLU Logo.png" class="mb-2" style="height: 35px;">
                    <button class="navbar-toggler ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                        <img src="../../img/icons/menus.png" alt="Menu" style="height: 25px;">
                    </button>
                </div>
                <a class="navbar-brand text-light">Document Reviewer</a>
                <form class="form">
                    <div class="row">
                        <div class="col">
                            <div class="input-group d-flex align-items-center">
                                <input class="form-control form-control-sm" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success btn-sm" type="submit">
                                        <img src="../../img/icons/magnifying-glass.png" alt="Search" style="height:25px">
                                    </button>
                                </div>
                                <div class="user_util position-relative ms-3 d-flex align-items-center">
                                    <img src="../../img/icons/profile-user.png" style="height: 30px; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#userModal">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </nav>

        <!-- User Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">User Options</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="window.location.href='settings.php'">Settings</button>
                            <button class="btn btn-danger" onclick="window.location.href='logout.php'">Log Out</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Reviewer Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminSidebarLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php include './rev_sidebar.php'; ?>
        </div>
    </div>
</body>
</html>