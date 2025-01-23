<?php include '../general/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reviewer Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/documents.css" rel="stylesheet">
    <!-- <link href="../../styles/index.css" rel="stylesheet"> -->
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
</head>

<body>
    <div class="recent-documents">
        <h2>Recent Documents</h2>
        <div class="documents">
            <div class="document unreviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 1">
                <p>Document 1</p>
            </div>
            <div class="document reviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 2">
                <p>Document 2</p>
            </div>
            <div class="document updated">
                <img src="https://via.placeholder.com/150x200" alt="Document 3">
                <p>Document 3</p>
            </div>
            <div class="document unreviewed"> --
                <img src="https://via.placeholder.com/150x200" alt="Document 4">
                <p>Document 4</p>
            </div>
        </div>
    </div>

    <div class="unreviewed-documents">
        <h2>Unreviewed Documents</h2>
        <div class="documents">
            <div class="document unreviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 5">
                <p>Document 5</p>
            </div>
            <div class="document unreviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 6">
                <p>Document 6</p>
            </div>
            <div class="document unreviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 7">
                <p>Document 7</p>
            </div>
            <div class="document unreviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 8">
                <p>Document 8</p>
            </div>
        </div>
    </div>

    <div class="reviewed-documents">
        <h2>Reviewed Documents</h2>
        <div class="documents">
            <div class="document reviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 9">
                <p>Document 9</p>
            </div>
            <div class="document reviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 10">
                <p>Document 10</p>
            </div>
            <div class="document reviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 11">
                <p>Document 11</p>
            </div>
            <div class="document reviewed">
                <img src="https://via.placeholder.com/150x200" alt="Document 12">
                <p>Document 12</p>
            </div>
        </div>
    </div>

    <div class="updated-documents">
        <h2>Updated Documents</h2>
        <div class="documents">
            <div class="document updated">
                <img src="https://via.placeholder.com/150x200" alt="Document 13">
                <p>Document 13</p>
            </div>
            <div class="document updated">
                <img src="https://via.placeholder.com/150x200" alt="Document 14">
                <p>Document 14</p>
            </div>
            <div class="document updated">
                <img src="https://via.placeholder.com/150x200" alt="Document 15">
                <p>Document 15</p>
            </div>
            <div class="document updated">
                <img src="https://via.placeholder.com/150x200" alt="Document 16">
                <p>Document 16</p>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom">
        <?php include '../general/footer.php'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../src/js/show_pwd.js"></script>

</body>

</html>