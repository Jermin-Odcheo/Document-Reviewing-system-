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
    <link rel="stylesheet" href="../../assets/styles/document_window.css">
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
                    <div id="commentList">
                        <!-- Comments will be added dynamically here -->
                    </div>
                    <div id="versionHistory"></div>

                    <!-- New Comment Form -->
                    <div id="commentForm">
                        <button id="addCommentBtn">Add Comment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load our scripts last -->
    <script src="../../../src/js/pdf.js"></script>
    <script src="../../../src/js/annotator.js" defer></script>

    <script>
document.addEventListener("DOMContentLoaded", () => {
  const viewerContainer = document.querySelector("#viewerContainer");
  const commentPanel = document.querySelector("#commentsPanel");

  let highlights = []; // Store all highlights
  let selectedText = "";
  let rangeToHighlight = null;  // Store the selected range temporarily

  // Handle adding a comment after text is highlighted
  function highlightText(range, comment) {
    // Create a span to wrap the highlighted text
    const span = document.createElement("span");
    span.classList.add("pdf-highlight");
    span.dataset.comment = comment;
    span.textContent = range.toString();
    span.style.backgroundColor = "rgba(255, 230, 0, 0.7)";

    // Safe wrapping for large selections
    const selectedTextFragment = range.cloneContents(); // Clone the selected contents
    span.appendChild(selectedTextFragment);  // Append cloned content into span

    // Insert the span into the range's start position (before the selected text)
    range.deleteContents();  // Delete the selected text
    range.insertNode(span);  // Insert the new span

    // Add event listener to the highlight for editing comments
    span.addEventListener("click", () => {
      const commentText = prompt("Edit comment:", comment);
      if (commentText) {
        span.dataset.comment = commentText;
        updateVersionHistory(commentText);
      }
    });

    // Save highlight and comment
    highlights.push({ text: range.toString(), comment });

    // Update comments panel
    updateCommentsPanel();
  }

  // Update comments panel
  function updateCommentsPanel() {
    commentPanel.innerHTML = "<h3>Comments</h3>";
    highlights.forEach((highlight, index) => {
      const div = document.createElement("div");
      div.classList.add("comment-item");
      div.innerHTML = `
        <p><strong>Highlight:</strong> ${highlight.text}</p>
        <p><strong>Comment:</strong> ${highlight.comment}</p>
      `;
      commentPanel.appendChild(div);
    });
  }

  // Update version history
  function updateVersionHistory(comment) {
    const historySection = document.querySelector("#versionHistory");
    const div = document.createElement("div");
    div.classList.add("version-history-item");
    div.innerHTML = `<p>${new Date().toLocaleString()}: ${comment}</p>`;
    historySection.appendChild(div);
  }

  // Event listener for text selection
  viewerContainer.addEventListener("mouseup", () => {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
      const range = selection.getRangeAt(0);
      selectedText = range.toString();

      if (selectedText.trim().length > 0) {
        rangeToHighlight = range;  // Store the selected range
      }
    }
  });

  // Event listener for Add Comment button
  const addCommentBtn = document.getElementById('addCommentBtn');
  addCommentBtn.addEventListener('click', function () {
    if (rangeToHighlight && selectedText.trim().length > 0) {
      const comment = prompt("Add a comment to this highlight:");
      if (comment) {
        highlightText(rangeToHighlight, comment);
        rangeToHighlight = null; // Reset the selected range after adding comment
      }
    } else {
      alert("Please highlight some text first.");
    }
  });
});

    </script>
</body>
<?php include '../general/footer.php';?>
</html>
