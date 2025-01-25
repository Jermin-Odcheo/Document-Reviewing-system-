// Initial setup for PDF and annotations
let pdfDoc = null;
let currentPage = 1;
let totalPages = 0;
let scale = 2.5;
let commentHistory = {}; // To store version history for each comment

// DOM Elements
const fileInput = document.getElementById("fileInput");
const uploadButton = document.getElementById("uploadButton");
const previousButton = document.getElementById("previous");
const nextButton = document.getElementById("next");
const pageNumberSpan = document.getElementById("pageNumber");
const pageCountSpan = document.getElementById("pageCount");
const viewer = document.getElementById("viewer");
const colorPicker = document.getElementById("colorPicker");
if (colorPicker) {
  colorPicker.value = "#ffeb3b";  // default color or your required default value
}

// Load the PDF document
function loadPDF(url) {
  const loadingTask = pdfjsLib.getDocument(url);
  loadingTask.promise.then(
    function (pdf) {
      pdfDoc = pdf;
      totalPages = pdf.numPages;
      pageCountSpan.textContent = totalPages;
      currentPage = 1;
      renderPage(currentPage);
    },
    function (reason) {
      console.error("Error loading PDF: ", reason);
    }
  );
}

// Render a specific page
function renderPage(num) {
  pdfDoc.getPage(num).then(function (page) {
    const viewport = page.getViewport({ scale: scale });
    // Clear previous viewer
    viewer.innerHTML = "";

    // Create a canvas element for the page
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");
    canvas.height = viewport.height;
    canvas.width = viewport.width;
    viewer.appendChild(canvas);

    // Render the page into the canvas
    const renderContext = {
      canvasContext: context,
      viewport: viewport,
    };
    const renderTask = page.render(renderContext);
    renderTask.promise.then(function () {
      // Page rendered
      pageNumberSpan.textContent = num;
    });

    // Setup text layer and highlighter
    setupTextLayer(page, viewport, canvas);
  });
}

function setupTextLayer(page, viewport, canvas) {
  const textLayerContainer = document.createElement('div');
  textLayerContainer.className = 'textLayerContainer';
  textLayerContainer.style.position = 'absolute';
  textLayerContainer.style.top = '0';
  textLayerContainer.style.left = '0';
  textLayerContainer.style.height = `${viewport.height}px`;
  textLayerContainer.style.width = `${viewport.width}px`;
  textLayerContainer.style.pointerEvents = 'auto';

  viewer.appendChild(textLayerContainer);

  const textLayerDiv = document.createElement('div');
  textLayerDiv.className = 'textLayer';
  textLayerDiv.style.position = 'relative';
  textLayerDiv.style.height = '100%';
  textLayerDiv.style.width = '100%';

  textLayerContainer.appendChild(textLayerDiv);

  page.getTextContent().then(function(textContent) {
    pdfjsLib.renderTextLayer({
      textContent: textContent,
      container: textLayerDiv,
      viewport: viewport,
      textDivs: []
    }).promise.then(function() {
      enableTextSelection(textLayerDiv);
    });
  });
}

function enableTextSelection(textLayerDiv) {
  textLayerDiv.addEventListener("mouseup", function (e) {
    const selection = window.getSelection();
    if (selection.isCollapsed) return;

    const range = selection.getRangeAt(0);
    const selectedText = selection.toString();

    // Create a highlight span
    const highlightSpan = document.createElement("span");
    highlightSpan.className = "highlight";
    highlightSpan.style.backgroundColor = colorPicker.value;

    try {
      range.surroundContents(highlightSpan);
    } catch (err) {
      console.error("Error highlighting text:", err);
    }

    // Clear selection
    selection.removeAllRanges();

    // Optionally, save the highlight data (e.g., to a server or local storage)
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const viewerContainer = document.querySelector("#viewerContainer");
  const commentPanel = document.querySelector("#commentsPanel");

  let highlights = []; // Store all highlights
  let selectedText = "";

// Highlight selected text
function highlightText(range, comment) {
  const span = document.createElement("span");
  span.classList.add("pdf-highlight");
  span.dataset.comment = comment;
  span.style.backgroundColor = "rgba(255, 230, 0, 0.7)";

  // Clone the content inside the range, ensuring it's text-based
  const contentFragment = range.cloneContents();
  span.appendChild(contentFragment); // Append the cloned content inside the span

  // Insert the span back into the range
  range.deleteContents();
  range.insertNode(span);

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
        const comment = prompt("Add a comment to this highlight:");
        if (comment) {
          highlightText(range, comment);
        }
      }
    }
  });
});

// Handle comments and versioning
const addCommentBtn = document.getElementById('addCommentBtn');
const commentInput = document.getElementById('commentInput');
const commentList = document.getElementById('commentList');

// Ensure commentInput exists before using it
if (commentInput) {
  // Add new comment with version tracking
  addCommentBtn.addEventListener('click', function() {
    const commentText = commentInput.value.trim();
    if (commentText) {
      const commentItem = document.createElement('div');
      commentItem.classList.add('comment-item');

      const commentBody = document.createElement('div');
      commentBody.classList.add('comment-body');
      commentBody.textContent = commentText;

      // Add version history
      const commentId = Date.now(); // Using timestamp as a unique ID
      if (!commentHistory[commentId]) {
        commentHistory[commentId] = []; // Initialize version history for new comment
      }
      commentHistory[commentId].push({ text: commentText, timestamp: new Date().toLocaleString() });

      const editBtn = document.createElement('button');
      editBtn.classList.add('edit-comment');
      editBtn.textContent = 'Edit';
      editBtn.onclick = () => editComment(commentItem, commentBody, commentId);

      const deleteBtn = document.createElement('button');
      deleteBtn.classList.add('delete-comment');
      deleteBtn.textContent = 'Delete';
      deleteBtn.onclick = () => deleteComment(commentItem, commentId);

      const actionButtons = document.createElement('div');
      actionButtons.classList.add('comment-actions');
      actionButtons.appendChild(editBtn);
      actionButtons.appendChild(deleteBtn);
      commentItem.appendChild(commentBody);
      commentItem.appendChild(actionButtons);

      commentList.appendChild(commentItem);

      // Clear input field
      commentInput.value = '';
    }
  });

  // Edit comment and create new version
  function editComment(commentItem, commentBody, commentId) {
    const newCommentText = prompt("Edit your comment:", commentBody.textContent);
    if (newCommentText && newCommentText.trim() !== "") {
      commentBody.textContent = newCommentText.trim();
      
      // Add new version
      commentHistory[commentId].push({
        text: newCommentText.trim(),
        timestamp: new Date().toLocaleString()
      });
    }
  }

  // Delete comment
  function deleteComment(commentItem, commentId) {
    const confirmDelete = confirm("Are you sure you want to delete this comment?");
    if (confirmDelete) {
      commentItem.remove();
      delete commentHistory[commentId]; // Remove comment history
    }
  }

  // View comment version history
  function viewVersionHistory(commentId) {
    const versions = commentHistory[commentId];
    const versionList = versions.map(version => {
      return `<li>${version.timestamp}: ${version.text}</li>`;
    }).join('');

    const modalContent = `
      <div class="history-modal">
        <div class="history-modal-content">
          <h3>Version History</h3>
          <ul>${versionList}</ul>
        </div>
      </div>
    `;
    document.body.innerHTML += modalContent;
  }
} else {
  console.error('commentInput element not found!');
}

// Example: load a default PDF
// loadPDF('path/to/default.pdf');
