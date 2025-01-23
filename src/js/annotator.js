// scripts/annotator.js

// Specify the workerSrc property
pdfjsLib.GlobalWorkerOptions.workerSrc =
  "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

let pdfDoc = null,
  currentPage = 1,
  totalPages = 0,
  scale = 2.5;

// References to DOM elements
const fileInput = document.getElementById("fileInput");
const uploadButton = document.getElementById("uploadButton");
const previousButton = document.getElementById("previous");
const nextButton = document.getElementById("next");
const pageNumberSpan = document.getElementById("pageNumber");
const pageCountSpan = document.getElementById("pageCount");
const viewer = document.getElementById("viewer");
const colorPicker = document.getElementById("colorPicker");

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
    // Create a container for the text layer
    const textLayerContainer = document.createElement('div');
    textLayerContainer.className = 'textLayerContainer';
    textLayerContainer.style.position = 'absolute';
    textLayerContainer.style.top = '0';
    textLayerContainer.style.left = '0';
    textLayerContainer.style.height = `${viewport.height}px`;
    textLayerContainer.style.width = `${viewport.width}px`;
    textLayerContainer.style.pointerEvents = 'auto'; // Enable interactions

    viewer.appendChild(textLayerContainer);

    // Create the text layer div inside the container
    const textLayerDiv = document.createElement('div');
    textLayerDiv.className = 'textLayer';
    textLayerDiv.style.position = 'relative';
    textLayerDiv.style.height = '100%';
    textLayerDiv.style.width = '100%';

    textLayerContainer.appendChild(textLayerDiv);

    // Get text content
    page.getTextContent().then(function(textContent) {
        // Render the text layer
        pdfjsLib.renderTextLayer({
            textContent: textContent,
            container: textLayerDiv,
            viewport: viewport,
            textDivs: []
        }).promise.then(function() {
            // Text layer rendered
            enableTextSelection(textLayerDiv);
        });
    });
}


// Enable text selection and add highlights
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

// Go to previous page
function onPreviousPage() {
  if (currentPage <= 1) return;
  currentPage--;
  renderPage(currentPage);
}

// Go to next page
function onNextPage() {
  if (currentPage >= totalPages) return;
  currentPage++;
  renderPage(currentPage);
}

// Handle file upload
uploadButton.addEventListener("click", function () {
  const file = fileInput.files[0];
  if (file && file.type === "application/pdf") {
    const fileURL = URL.createObjectURL(file);
    loadPDF(fileURL);
  } else {
    alert("Please upload a valid PDF file.");
  }
});

// Event listeners for navigation buttons
previousButton.addEventListener("click", onPreviousPage);
nextButton.addEventListener("click", onNextPage);

// Optionally, load a default PDF
// loadPDF('path/to/default.pdf');
