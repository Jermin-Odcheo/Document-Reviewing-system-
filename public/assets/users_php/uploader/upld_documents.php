<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reviewer Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/document.css" rel="stylesheet">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
</head>

<body>
    <header>
        <?php include "./upld_header.php"; ?>
    </header>
    <div class="container mt-4">
        <div class="template-gallery mb-4">
            <h5>Start a new document</h5>
            <div class="d-flex flex-wrap justify-content-start align-items-start gap-4">
                <!-- Upload Document -->
                <div class="template-item text-center">
                    <div class="position-relative">
                        <img alt="Upload Document" src="https://storage.googleapis.com/a1aa/image/oltMoPJVGL5pCBiT0GDGhK8EhcDDYhotebiMGDce4RQvi2HUA.jpg" 
                             class="document-template" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <img alt="File icon" src="../../img/icons/file.png" class="file-icon">
                    </div>
                    <p>Upload Document</p>
                </div>

                <!-- Blank Documents -->
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="template-item text-center">
                        <div class="position-relative">
                            <img alt="Blank document template" src="https://storage.googleapis.com/a1aa/image/oltMoPJVGL5pCBiT0GDGhK8EhcDDYhotebiMGDce4RQvi2HUA.jpg" class="document-template">
                        </div>
                        <p>Blank document</p>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="recent-documents">
            <h5>
                Recent documents
            </h5>
            <div class="row">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img alt="Document thumbnail" class="card-img-top" height="300" src="https://storage.googleapis.com/a1aa/image/BvmLfgo9pBTADC0fcL7D1fMqBHOCTiq9PvcI4NR8ZnNjFtPoA.jpg" width="200" />
                            <div class="card-body">
                                <h6 class="card-title">
                                    Document<?= $i ?>
                                </h6>
                                <p class="card-text">
                                    <?= date('M d, Y', strtotime('-' . (7 - $i) . ' days')) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom">
        <?php include "../general/footer.php" ?>
    </footer>
    <!-- Modal for Upload Document -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Documents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Include uploads.php -->
                    <?php include "./uploads.php"; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
