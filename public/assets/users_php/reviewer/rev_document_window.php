<!DOCTYPE html>
<html>
<head>
    <title>PDF Annotator</title>
    <!-- Load PDF.js first -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <!-- Load PDF.js viewer -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.js"></script>
    <!-- Load styles after scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.css">
    <link rel="stylesheet" href="../../styles/document_window.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div id="toolbar">
            <div id="toolbarViewer">
                <div class="upload-container">
                    <input type="file" id="fileInput" accept="application/pdf" />
                    <button id="uploadButton" type="button">Upload PDF</button>
                </div>
                <div class="splitToolbarButton">
                    <button id="previous" class="toolbarButton">Previous</button>
                    <button id="next" class="toolbarButton">Next</button>
                </div>
                <label>Page: <span id="pageNumber">1</span> / <span id="pageCount">1</span></label>
                <div class="annotationControls">
                    <input type="color" id="colorPicker" value="#ffeb3b" title="Choose highlight color">
                </div>
            </div>
        </div>

        <div id="outerContainer">
            <div id="mainContainer">
                <div id="viewerContainer">
                    <div id="viewer" class="pdfViewer"></div>
                </div>
                <div id="commentsPanel">
                    <div class="comments-header">
                        <h3>Comments</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load our scripts last -->
    <script src="../../../../src/js/pdf.js"></script>
    <script src="../../../../src/js/annotator.js"></script>
</body>
<?php include '../general/footer.php';?>
</html>