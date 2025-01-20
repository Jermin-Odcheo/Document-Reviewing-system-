if (typeof pdfjsLib === 'undefined' || typeof pdfjsViewer === 'undefined') {
    alert('PDF.js libraries not loaded. Please check your internet connection and refresh.');
} else {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    class PDFViewer {
        constructor() {
            if (!pdfjsLib || !pdfjsViewer) {
                throw new Error('PDF.js libraries not loaded');
            }

            // Initialize properties
            this.pdfDoc = null;
            this.pageNum = 1;
            this.scale = 1.0;

            // Initialize elements
            this.viewer = document.getElementById('viewer');
            this.viewerContainer = document.getElementById('viewerContainer');
            this.previousButton = document.getElementById('previous');
            this.nextButton = document.getElementById('next');
            this.pageNumber = document.getElementById('pageNumber');
            this.pageCount = document.getElementById('pageCount');
            this.fileInput = document.getElementById('fileInput');
            this.uploadButton = document.getElementById('uploadButton');

            // Initialize event bus
            this.eventBus = new pdfjsViewer.EventBus();

            this.initializeViewer();
            this.bindEvents();
        }

        initializeViewer() {
            try {
                // Initialize link service
                this.linkService = new pdfjsViewer.PDFLinkService({
                    eventBus: this.eventBus,
                });

                // Initialize PDF viewer with explicit container and viewer elements
                this.pdfViewer = new pdfjsViewer.PDFViewer({
                    container: this.viewerContainer,
                    viewer: this.viewer,
                    eventBus: this.eventBus,
                    linkService: this.linkService,
                    textLayerMode: 2,
                    removePageBorders: false,
                    renderer: 'canvas',
                    l10n: pdfjsViewer.NullL10n,
                    useOnlyCssZoom: false,
                    enablePrintAutoRotate: false,
                    scrollMode: 0,  // Single page scrolling
                    singlePageMode: true,  // Single page mode
                    defaultViewport: { scale: 1 },
                    enableScripting: false, // Disable default toolbar
                    toolbar: null // Disable default toolbar
                });

                // Set up link service
                this.linkService.setViewer(this.pdfViewer);

                // Set initial zoom when document is loaded
                this.eventBus.on('pagesinit', () => {
                    this.pdfViewer.currentScaleValue = 'auto';
                });

                console.log('Viewer initialized successfully');
            } catch (error) {
                console.error('Error initializing viewer:', error);
                throw error;
            }
        }

        bindEvents() {
            // File Upload Events
            this.uploadButton.onclick = () => this.fileInput.click();
            this.fileInput.onchange = async (event) => {
                const file = event.target.files[0];
                if (file && file.type === 'application/pdf') {
                    await this.loadPDF(file);
                } else {
                    alert('Please select a valid PDF file.');
                }
            };

            // Navigation Events
            this.previousButton.addEventListener('click', () => {
                if (this.pdfViewer.currentPageNumber <= 1) return;
                const newPageNum = this.pdfViewer.currentPageNumber - 1;
                this.pdfViewer.currentPageNumber = newPageNum;
                this.pdfViewer.scrollPageIntoView({ pageNumber: newPageNum });
                this.updatePageNumber();
            });

            this.nextButton.addEventListener('click', () => {
                if (!this.pdfDoc || this.pdfViewer.currentPageNumber >= this.pdfDoc.numPages) return;
                const newPageNum = this.pdfViewer.currentPageNumber + 1;
                this.pdfViewer.currentPageNumber = newPageNum;
                this.pdfViewer.scrollPageIntoView({ pageNumber: newPageNum });
                this.updatePageNumber();
            });

            // Page Change Event
            this.eventBus.on('pagechanging', (evt) => {
                this.updatePageNumber();
            });
        }

        updatePageNumber() {
            requestAnimationFrame(() => {
                // Update the display of the page number and count
                this.pageNumber.textContent = this.pdfViewer.currentPageNumber;
                this.pageCount.textContent = this.pdfDoc ? this.pdfDoc.numPages : '-';

                // Update button states
                this.previousButton.disabled = this.pdfViewer.currentPageNumber <= 1;
                this.nextButton.disabled = this.pdfViewer.currentPageNumber >= (this.pdfDoc?.numPages || 1);
            });
        }

        async loadPDF(file) {
            try {
                console.log('Loading PDF:', file.name);
                const arrayBuffer = await file.arrayBuffer();
                const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });

                this.pdfDoc = await loadingTask.promise;
                console.log('PDF loaded successfully');

                this.updatePageNumber();

                await this.pdfViewer.setDocument(this.pdfDoc);
                this.linkService.setDocument(this.pdfDoc);

                // Dispatch custom event when PDF is loaded
                window.dispatchEvent(new CustomEvent('pdfloaded', {
                    detail: { scale: this.pdfViewer.currentScale }
                }));

            } catch (error) {
                console.error('Error loading PDF:', error);
                alert('Error loading PDF: ' + error.message);
            }
        }
    }

    // Initialize viewer when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeViewer);
    } else {
        initializeViewer();
    }

    function initializeViewer() {
        try {
            window.pdfViewer = new PDFViewer();
            console.log('PDF Viewer initialized');

            // Initialize annotator after PDF is loaded
            window.addEventListener('pdfloaded', function() {
                if (!window.annotator) {
                    window.annotator = new PDFAnnotator();
                }
            });
        } catch (error) {
            console.error('Error initializing PDF Viewer:', error);
            alert('Error initializing PDF viewer: ' + error.message);
        }
    }
}

class PDFAnnotator {
    constructor() {
        this.activeTool = null;
        this.pdfViewer = window.pdfViewer.pdfViewer;
        this.eventBus = window.pdfViewer.eventBus;
        this.setupToolbar();
        this.bindEvents();
    }

    setupToolbar() {
        const toolbar = document.getElementById('toolbarViewer');
        const annotationControls = toolbar.querySelector('.annotationControls');

        // Add highlighter button
        const highlighterBtn = document.createElement('button');
        highlighterBtn.innerHTML = '<i class="fas fa-highlighter"></i> Highlighter';
        highlighterBtn.title = 'Highlight text';
        highlighterBtn.classList.add('toolbarButton');
        highlighterBtn.id = 'highlighterTool';
        highlighterBtn.onclick = () => this.setActiveTool('highlighter', highlighterBtn);

        // Insert the highlighter button at the beginning of annotationControls
        annotationControls.insertBefore(highlighterBtn, annotationControls.firstChild);
    }

    setActiveTool(toolName, button) {
        // Remove active class from all buttons
        document.querySelectorAll('.toolbarButton').forEach(btn => {
            btn.classList.remove('active');
        });

        if (this.activeTool === toolName) {
            this.activeTool = null;
            document.getElementById('viewerContainer').classList.remove('highlighting');
        } else {
            this.activeTool = toolName;
            button.classList.add('active');
            document.getElementById('viewerContainer').classList.add('highlighting');
        }
    }

    bindEvents() {
        // Listen for text selection events
        this.eventBus.on('textlayerrendered', (evt) => {
            const page = evt.source.textLayer.div.closest('.page');
            if (page) {
                // Create annotation layer if it doesn't exist
                let annotationLayer = page.querySelector('.annotationLayer');
                if (!annotationLayer) {
                    annotationLayer = document.createElement('div');
                    annotationLayer.className = 'annotationLayer';
                    page.appendChild(annotationLayer);
                }
            }
        });

        document.addEventListener('mouseup', (event) => {
            if (this.activeTool === 'highlighter') {
                this.handleHighlight(event);
            }
        });
    }

    handleHighlight(event) {
        const selection = window.getSelection();
        if (!selection.rangeCount || selection.isCollapsed) return;

        const range = selection.getRangeAt(0);
        const textLayerElements = document.querySelectorAll('.textLayer');

        // Check if selection is within text layer
        let isInTextLayer = false;
        let targetTextLayer = null;
        textLayerElements.forEach(textLayer => {
            if (textLayer.contains(range.commonAncestorContainer)) {
                isInTextLayer = true;
                targetTextLayer = textLayer;
            }
        });

        if (!isInTextLayer || !targetTextLayer) return;

        const page = targetTextLayer.closest('.page');
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
            const highlight = document.createElement('div');
            highlight.className = 'pdf-highlight';

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
            highlight.style.backgroundColor = 'rgba(255, 255, 0, 0.3)';

            // Add to annotation layer
            const annotationLayer = page.querySelector('.annotationLayer');
            if (annotationLayer) {
                annotationLayer.appendChild(highlight);
            }
        }

        // Clear selection
        selection.removeAllRanges();
    }
}