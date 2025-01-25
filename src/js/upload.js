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
                if (file.size > 50 * 1024 * 1024) {
                    alert(`${file.name} exceeds the 50MB size limit.`);
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
            const k = 199024;
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
            const statusText = container.querySelector('.upload-status');

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const progress = (e.loaded / e.total) * 100;
                    fileData.progress = progress;
                    progressBar.style.width = `${progress}%`;
                    statusText.textContent = `Uploading: ${Math.round(progress)}%`;
                }
            });

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    fileData.status = 'completed';
                    statusText.textContent = 'Upload Complete';
                    progressBar.style.width = '100%';
                }
            };

            xhr.open('POST', '/upload', true);
            xhr.send(formData);
        }

        function togglePause(fileId) {
            const fileData = uploadQueue.get(fileId);
            if (fileData.status === 'uploading') {
                // Pause upload
            } else {
                uploadFile(fileId);
            }
        }

        function resetUpload(fileId) {
            const fileData = uploadQueue.get(fileId);
            fileData.progress = 0;
            fileData.status = 'pending';
            const container = document.getElementById(fileId);
            const progressBar = container.querySelector('.progress-bar');
            const statusText = container.querySelector('.upload-status');

            progressBar.style.width = '0%';
            statusText.textContent = 'Ready to Upload';
        }

        function removeFile(fileId) {
            uploadQueue.delete(fileId);
            const container = document.getElementById(fileId);
            container.remove();
            updateGlobalActions();
        }

        function clearFiles() {
            uploadQueue.clear();
            document.getElementById('fileList').innerHTML = '';
            updateGlobalActions();
        }
