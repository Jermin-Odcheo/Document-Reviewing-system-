<html>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reviewer Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/dashboard.css" rel="stylesheet">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
 </head>
 <body>
    <?php include "../general/user_header.php";?>
    <div class="d-flex">
        <?php include "./admin_sidebar.php"; ?>
        <div class="flex-grow-1">
            <div class="content">
                <h2>
                    Dashboard
                </h2>
                <div class="d-flex justify-content-around">
                    <div class="card">
                        <h3>
                            Pending Reviews
                        </h3>
                        <h1>
                            8
                        </h1>
                    </div>
                    <div class="card">
                        <h3>
                            Completed Reviews
                        </h3>
                        <h1>
                            20
                        </h1>
                    </div>
                    <div class="card">
                        <h3>
                            Unreviewed Documents
                        </h3>
                        <h1>
                            3
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php include "../general/footer.php"; ?>
    </footer>
 </body>
</html>
