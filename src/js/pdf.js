if (typeof pdfjsLib === "undefined" || typeof pdfjsViewer === "undefined") {
  alert(
    "PDF.js libraries not loaded. Please check your internet connection and refresh."
  );
} else {
  pdfjsLib.GlobalWorkerOptions.workerSrc =
    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

  class PDFViewer {
    constructor() {
      if (!pdfjsLib || !pdfjsViewer) {
        throw new Error("PDF.js libraries not loaded");
      }

      // Initialize properties
      this.pdfDoc = null;
      this.pageNum = 1;
      this.scale = 1.0;
      this.activeTool = null; // For annotation tools

      // Initialize elements
      this.viewer = document.getElementById("viewer");
      this.viewerContainer = document.getElementById("viewerContainer");
      this.previousButton = document.getElementById("previous");
      this.nextButton = document.getElementById("next");
      this.pageNumber = document.getElementById("pageNumber");
      this.pageCount = document.getElementById("pageCount");
      this.fileInput = document.getElementById("fileInput");
      this.uploadButton = document.getElementById("uploadButton");
      this.toolbar = document.getElementById("toolbarViewer"); // Assuming toolbarViewer exists
      this.annotationControls = this.toolbar.querySelector(
        ".annotationControls"
      );

      // Initialize event bus
      this.eventBus = new pdfjsViewer.EventBus();

      // Initialize viewer and annotator features
      this.initializeViewer();
      this.setupAnnotator(); // Integrated Annotator
      this.bindEvents();
    }

    initializeViewer() {
      try {
        // Initialize link service
        this.linkService = new pdfjsViewer.PDFLinkService({
          eventBus: this.eventBus,
        });

        // Initialize PDF viewer
        this.pdfViewer = new pdfjsViewer.PDFViewer({
          container: this.viewerContainer,
          viewer: this.viewer,
          eventBus: this.eventBus,
          linkService: this.linkService,
          textLayerMode: 2,
          enableTextLayer: true,
          removePageBorders: false,
          renderer: "canvas",
          l10n: pdfjsViewer.NullL10n,
        });

        this.linkService.setViewer(this.pdfViewer);

        // Set initial zoom and force layout recalculation
        this.eventBus.on("pagesinit", () => {
          this.pdfViewer.currentScaleValue = "auto";
          this.forceReflow();
        });

        this.eventBus.on("textlayerrendered", (evt) => {
          console.log(`Text layer rendered for page ${evt.pageNumber}`);
          // Handle annotation layer creation if needed
          this.createAnnotationLayer(evt);
        });

        console.log("Viewer initialized successfully");
      } catch (error) {
        console.error("Error initializing viewer:", error);
        throw error;
      }
    }

    setupAnnotator() {
      this.setupToolbar();
      this.bindAnnotatorEvents();
    }

    setupToolbar() {
      // Add highlighter button
      const highlighterBtn = document.createElement("button");
      highlighterBtn.innerHTML =
        '<i class="fas fa-highlighter"></i> Highlighter';
      highlighterBtn.title = "Highlight text";
      highlighterBtn.classList.add("toolbarButton");
      highlighterBtn.id = "highlighterTool";
      highlighterBtn.onclick = () =>
        this.setActiveTool("highlighter", highlighterBtn);

      // Insert the highlighter button at the beginning of annotationControls
      this.annotationControls.insertBefore(
        highlighterBtn,
        this.annotationControls.firstChild
      );
    }

    setActiveTool(toolName, button) {
      // Remove active class from all buttons
      document.querySelectorAll(".toolbarButton").forEach((btn) => {
        btn.classList.remove("active");
      });

      if (this.activeTool === toolName) {
        this.activeTool = null;
        this.viewerContainer.classList.remove("highlighting");
      } else {
        this.activeTool = toolName;
        button.classList.add("active");
        this.viewerContainer.classList.add("highlighting");
      }
    }

    bindAnnotatorEvents() {
      // Listen for text selection events
      document.addEventListener("mouseup", (event) => {
        if (this.activeTool === "highlighter") {
          this.handleHighlight(event);
        }
      });

      // Listen for scale changes to reflow annotations if necessary
      this.eventBus.on("scalechanging", () => {
        console.log("Scale changed, notifying annotator...");
        window.dispatchEvent(new CustomEvent("scalechanged"));
      });
    }

    createAnnotationLayer(evt) {
      const page = evt.source.textLayer.div.closest(".page");
      if (page) {
        // Create annotation layer if it doesn't exist
        let annotationLayer = page.querySelector(".annotationLayer");
        if (!annotationLayer) {
          annotationLayer = document.createElement("div");
          annotationLayer.className = "annotationLayer";
          annotationLayer.style.position = "absolute";
          annotationLayer.style.top = "0";
          annotationLayer.style.left = "0";
          annotationLayer.style.width = "100%";
          annotationLayer.style.height = "100%";
          annotationLayer.style.pointerEvents = "none"; // Allow clicks to pass through
          page.appendChild(annotationLayer);
        }
      }
    }

    bindEvents() {
      // File Upload Events
      this.uploadButton.onclick = () => this.fileInput.click();
      this.fileInput.onchange = async (event) => {
        const file = event.target.files[0];
        if (file && file.type === "application/pdf") {
          await this.loadPDF(file);
        } else {
          alert("Please select a valid PDF file.");
        }
      };

      // Navigation Events
      this.previousButton.addEventListener("click", () => {
        if (this.pdfViewer.currentPageNumber <= 1) return;
        this.pdfViewer.currentPageNumber -= 1;
        this.updatePageNumber();
      });

      this.nextButton.addEventListener("click", () => {
        if (this.pdfViewer.currentPageNumber >= this.pdfDoc.numPages) return;
        this.pdfViewer.currentPageNumber += 1;
        this.updatePageNumber();
      });

      // Handle resize events
      window.addEventListener("resize", () => {
        console.log("Window resized. Updating scale and reflowing.");
        this.pdfViewer.currentScaleValue = "auto";
        this.forceReflow();
      });
    }

    forceReflow() {
      // Force reflow to fix alignment issues
      const viewerContainer = this.viewerContainer;
      viewerContainer.style.display = "none"; // Temporarily hide
      void viewerContainer.offsetHeight; // Trigger reflow
      viewerContainer.style.display = ""; // Restore display
    }

    updatePageNumber() {
      requestAnimationFrame(() => {
        this.pageNumber.textContent = this.pdfViewer.currentPageNumber;
        this.pageCount.textContent = this.pdfDoc ? this.pdfDoc.numPages : "-";
      });
    }

    async loadPDF(file) {
      try {
        console.log("Loading PDF:", file.name);
        const arrayBuffer = await file.arrayBuffer();
        const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });

        this.pdfDoc = await loadingTask.promise;
        console.log("PDF loaded successfully");

        await this.pdfViewer.setDocument(this.pdfDoc);
        this.linkService.setDocument(this.pdfDoc);

        this.updatePageNumber();

        // Dispatch custom event when PDF is loaded
        window.dispatchEvent(new CustomEvent("pdfloaded"));
      } catch (error) {
        console.error("Error loading PDF:", error);
        alert("Error loading PDF: " + error.message);
      }
    }

    handleHighlight(event) {
      const selection = window.getSelection();
      if (!selection.rangeCount || selection.isCollapsed) return;

      const range = selection.getRangeAt(0);
      const textLayerElements = document.querySelectorAll(".textLayer");

      // Check if selection is within text layer
      let isInTextLayer = false;
      let targetTextLayer = null;
      textLayerElements.forEach((textLayer) => {
        if (textLayer.contains(range.commonAncestorContainer)) {
          isInTextLayer = true;
          targetTextLayer = textLayer;
        }
      });

      if (!isInTextLayer || !targetTextLayer) return;

      const page = targetTextLayer.closest(".page");
      if (!page) return;

      // Get page viewport for correct scaling
      const pageNumber = parseInt(page.dataset.pageNumber);
      const pageView = this.pdfViewer.getPageView(pageNumber - 1);
      const viewport = pageView.viewport;

      // Create highlight elements
      const rects = range.getClientRects();
      for (let i = 0; i < rects.length; i++) {
        const rect = rects[i];

        // Create highlight element
        const highlight = document.createElement("div");
        highlight.className = "pdf-highlight";

        // Convert screen coordinates to PDF coordinates
        const boundingRect = targetTextLayer.getBoundingClientRect();
        const x = (rect.left - boundingRect.left) / viewport.scale;
        const y = (rect.top - boundingRect.top) / viewport.scale;
        const width = rect.width / viewport.scale;
        const height = rect.height / viewport.scale;

        // Position highlight
        highlight.style.left = `${x}px`;
        highlight.style.top = `${y}px`;
        highlight.style.width = `${width}px`;
        highlight.style.height = `${height}px`;
        highlight.style.backgroundColor = "rgba(255, 255, 0, 0.3)";
        highlight.style.pointerEvents = "none"; // Allow clicks to pass through

        // Add to annotation layer
        const annotationLayer = page.querySelector(".annotationLayer");
        if (annotationLayer) {
          annotationLayer.appendChild(highlight);
        }
      }

      selection.removeAllRanges();
    }
  }

  // Initialize viewer when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeViewer);
  } else {
    initializeViewer();
  }

  function initializeViewer() {
    try {
      window.pdfViewerInstance = new PDFViewer();
      console.log("PDF Viewer initialized");

      // Initialize annotator is no longer needed as it's integrated
      // If you have other annotator-related initializations, add them here
    } catch (error) {
      console.error("Error initializing PDF Viewer:", error);
      alert("Error initializing PDF viewer: " + error.message);
    }
  }
}
