<?php include '../general/header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reviewer Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/documents.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
</head>
<body>
    <div class="row">
    <div class="cols-xs-6">
        <div class="sidebar">
        <?php include './rev_sidebar.php'?>
        </div>
        <div class="col-xs-6">
            <div class="col-md-1" alt="newly_uploaded">
                newly uploaded
            </div>
            <div class="col-md-1" alt="recents">
                recents
            </div>
        </div>
    </div>
</div>
</body>
<?php include '../general/footer.php';?>
</html>