<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Upload Modal Actions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        .upload-box {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            transition: border-color 0.3s ease;
        }

        .upload-box.dragover {
            border-color: #4a69bd;
            background-color: #f1f4fb;
        }

        .upload-box i {
            font-size: 50px;
            color: #4a69bd;
        }

        .file-info {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 10px;
            margin-top: 10px;
        }

        .upload-actions {
            margin-top: 5px;
        }

        .upload-actions button {
            padding: 2px 8px;
            font-size: 0.8rem;
        }

        .file-format-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .upload-status {
            font-size: 0.85rem;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Upload Documents</h3>

                        <!-- Dropzone -->
                        <div class="upload-box" id="dropZone">
                            <i class="fas fa-cloud-upload-alt mb-3"></i>
                            <p class="mb-2">Drag & drop your PDF files here</p>
                            <div class="upload-actions mb-3">
                                <button class="btn btn-primary btn-sm" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-folder-open me-1"></i>Browse Files
                                </button>
                            </div>
                            <small class="text-muted">Only PDF files are allowed</small>
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

    <script>
        let uploadQueue = new Map();
        let activeUploads = new Map();

        // Initialize drag and drop
        const dropZone = document.getElementById('dropZone');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        dropZone.addEventListener('dragenter', highlight, false);
        dropZone.addEventListener('dragover', highlight, false);
        dropZone.addEventListener('dragleave', unhighlight, false);
        dropZone.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('dragover');
        }

        function unhighlight(e) {
            dropZone.classList.remove('dragover');
        }

        // File handling
        document.getElementById('fileInput').addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });

        function handleDrop(e) {
            unhighlight(e);
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        function handleFiles(fileList) {
            Array.from(fileList).forEach(file => {
                if (file.type !== 'application/pdf') {
                    alert(`${file.name} is not a PDF file. Only PDF files are allowed.`);
                    return;
                }
                addFileToQueue(file);
            });
            updateGlobalActions();
        }

        function addFileToQueue(file) {
            const fileId = `file-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            uploadQueue.set(fileId, {
                file: file,
                status: 'pending',
                progress: 0
            });
            displayFile(fileId, file);
        }

        function displayFile(fileId, file) {
            const template = document.getElementById('fileItemTemplate');
            const fileItem = template.content.cloneNode(true);
            const container = fileItem.querySelector('.file-info');

            container.id = fileId;
            container.querySelector('.filename').textContent = file.name;
            container.querySelector('.file-details').textContent =
                `PDF Document • ${formatFileSize(file.size)}`;

            // Setup action buttons
            container.querySelector('.pause-btn').onclick = () => togglePause(fileId);
            container.querySelector('.reset-btn').onclick = () => resetUpload(fileId);
            container.querySelector('.remove-btn').onclick = () => removeFile(fileId);

            document.getElementById('fileList').appendChild(container);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function updateGlobalActions() {
            const hasFiles = uploadQueue.size > 0;
            document.getElementById('globalActions').style.display = hasFiles ? 'flex' : 'none';
        }

        function startUpload() {
            uploadQueue.forEach((fileData, fileId) => {
                if (fileData.status === 'pending') {
                    uploadFile(fileId);
                }
            });
        }

        function uploadFile(fileId) {
            const fileData = uploadQueue.get(fileId);
            if (!fileData) return;

            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append('files[]', fileData.file);

            const container = document.getElementById(fileId);
            const progressBar = container.querySelector('.progress-bar');
            const statusDiv = container.querySelector('.upload-status');

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent.toFixed(1) + '%';
                    statusDiv.textContent = 'Uploading...';
                }
            });

            xhr.onload = function() {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        const fileResponse = response.find(item => item.file === fileData.file.name);
                        if (fileResponse && fileResponse.status === 'success') {
                            progressBar.classList.add('bg-success');
                            statusDiv.textContent = 'Upload Complete and Saved!';
                        } else {
                            progressBar.classList.add('bg-danger');
                            statusDiv.textContent = fileResponse.message || 'Upload Failed';
                        }
                    } else {
                        progressBar.classList.add('bg-danger');
                        statusDiv.textContent = 'Upload Failed';
                    }
                } catch (e) {
                    progressBar.classList.add('bg-danger');
                    statusDiv.textContent = 'Unexpected server response';
                    console.error('Response parsing error:', e, xhr.responseText);
                }
            };



            xhr.onerror = function() {
                progressBar.classList.add('bg-danger');
                statusDiv.textContent = 'Upload Failed';
            };

            xhr.open('POST', 'process_upload.php', true);
            xhr.send(formData);
            activeUploads.set(fileId, xhr);
            fileData.status = 'uploading';
        }

        function togglePause(fileId) {
            const xhr = activeUploads.get(fileId);
            const fileData = uploadQueue.get(fileId);
            const btn = document.querySelector(`#${fileId} .pause-btn i`);

            if (fileData.status === 'uploading') {
                xhr.abort();
                fileData.status = 'paused';
                btn.classList.replace('fa-pause', 'fa-play');
            } else if (fileData.status === 'paused') {
                uploadFile(fileId);
                btn.classList.replace('fa-play', 'fa-pause');
            }
        }

        function resetUpload(fileId) {
            const xhr = activeUploads.get(fileId);
            if (xhr) xhr.abort();

            const container = document.getElementById(fileId);
            const progressBar = container.querySelector('.progress-bar');
            const statusDiv = container.querySelector('.upload-status');

            progressBar.style.width = '0%';
            progressBar.textContent = '';
            progressBar.className = 'progress-bar';
            statusDiv.textContent = 'Ready to upload';

            const fileData = uploadQueue.get(fileId);
            fileData.status = 'pending';
            fileData.progress = 0;
        }

        function removeFile(fileId) {
            const xhr = activeUploads.get(fileId);
            if (xhr) xhr.abort();

            document.getElementById(fileId).remove();
            uploadQueue.delete(fileId);
            activeUploads.delete(fileId);
            updateGlobalActions();
        }

        function clearFiles() {
            activeUploads.forEach(xhr => xhr.abort());
            uploadQueue.clear();
            activeUploads.clear();
            document.getElementById('fileList').innerHTML = '';
            updateGlobalActions();
        }
    </script>
</body>

</html>