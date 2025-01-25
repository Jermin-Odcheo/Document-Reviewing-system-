<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Upload Modal Actions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script href="../../../../src/js/upload.js" rel="script"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Upload</h3>

                        <!-- Dropzone -->
                        <div class="upload-box" id="dropZone">
                            <i class="fas fa-cloud-upload-alt mb-3"></i>
                            <p class="mb-2">Drag & drop your PDF files here</p>
                            <div class="upload-actions mb-3">
                                <button class="btn btn-primary btn-sm" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-folder-open me-1"></i>Browse Files
                                </button>
                            </div>
                            <small class="text-muted">Only PDF files (Max 50MB) are allowed</small>
                            <input type="file" id="fileInput" multiple accept=".pdf" style="display: none;">
                        </div>

                        <!-- Upload Handler -->
                        <div id="uploadHandler" class="mt-4">
                            <div id="fileList">
                                <!-- Files will be listed here -->
                            </div>

                            <!-- Global Upload Actions -->
                            <div class="d-flex justify-content-end mt-3" id="globalActions" style="display: none;">
                                <button class="btn btn-secondary me-2" onclick="clearFiles()">
                                    <i class="fas fa-times me-1"></i>Clear All
                                </button>
                                <button class="btn btn-primary" id="uploadButton" onclick="startUpload()">
                                    <i class="fas fa-upload me-1"></i>Upload All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Item Template -->
    <template id="fileItemTemplate">
        <div class="file-info">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-pdf file-format-icon text-danger"></i>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 filename"></h6>
                            <small class="text-muted file-details"></small>
                        </div>
                        <div class="upload-actions">
                            <button class="btn btn-outline-primary btn-sm pause-btn">
                                <i class="fas fa-pause"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm reset-btn">
                                <i class="fas fa-redo"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm remove-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="upload-status mt-1"></div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</body>

</html>
