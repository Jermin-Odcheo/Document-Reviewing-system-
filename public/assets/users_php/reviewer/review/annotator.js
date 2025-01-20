class PDFAnnotator {
    constructor() {
        this.isHighlighting = false;
        this.highlights = [];
        this.selectedHighlight = null;
        this.currentColor = '#ffeb3b';
        this.pdfViewer = window.pdfViewer.pdfViewer;
        this.eventBus = window.pdfViewer.eventBus;
        
        // Get UI elements
        this.commentForm = document.getElementById('commentForm');
        this.commentInput = document.getElementById('commentInput');
        this.commentList = document.getElementById('commentList');
        this.viewerContainer = document.getElementById('viewerContainer');
        this.colorPicker = document.getElementById('colorPicker');
        
        // Bind the event handler
        this.handleSelectionBound = this.handleSelection.bind(this);
        
        this.init();
    }

    init() {
        this.setupToolbar();
        this.setupColorPicker();
        this.bindEvents();
        console.log('PDFAnnotator initialized');
    }

    setupToolbar() {
        const highlighterBtn = document.getElementById('highlighterTool');
        if (highlighterBtn) {
            highlighterBtn.addEventListener('click', () => {
                this.toggleHighlighting();
            });
        }
    }

    setupColorPicker() {
        if (this.colorPicker) {
            this.colorPicker.addEventListener('change', (e) => {
                this.currentColor = e.target.value;
                console.log('Color changed to:', this.currentColor);
            });
        }
    }

    toggleHighlighting() {
        this.isHighlighting = !this.isHighlighting;
        const highlighterButton = document.getElementById('highlighterTool');
        
        if (this.isHighlighting) {
            console.log('Highlighting mode enabled');
            highlighterButton.classList.add('active');
            document.addEventListener('mouseup', this.handleSelectionBound);
        } else {
            console.log('Highlighting mode disabled');
            highlighterButton.classList.remove('active');
            document.removeEventListener('mouseup', this.handleSelectionBound);
        }
    }

    handleSelection(event) {
        if (!this.isHighlighting) return;

        const selection = window.getSelection();
        if (!selection.rangeCount || selection.isCollapsed) return;

        const range = selection.getRangeAt(0);
        const textContent = range.toString().trim();
        if (!textContent) return;

        // Find the text layer
        let textLayer = null;
        let node = range.commonAncestorContainer;
        while (node && !textLayer) {
            if (node.classList && node.classList.contains('textLayer')) {
                textLayer = node;
            }
            node = node.parentNode;
        }

        if (!textLayer) {
            console.log('No text layer found');
            return;
        }

        const page = textLayer.closest('.page');
        if (!page) {
            console.log('No page found');
            return;
        }

        const pageNumber = parseInt(page.dataset.pageNumber);
        const pageView = this.pdfViewer.getPageView(pageNumber - 1);
        if (!pageView) {
            console.log('No page view found');
            return;
        }

        console.log('Creating highlight on page:', pageNumber);

        const viewport = pageView.viewport;
        const textLayerRect = textLayer.getBoundingClientRect();
        const selectionRects = Array.from(range.getClientRects());

        const highlights = selectionRects.map(rect => {
            return {
                left: (rect.left - textLayerRect.left) / viewport.scale,
                top: (rect.top - textLayerRect.top) / viewport.scale,
                width: rect.width / viewport.scale,
                height: rect.height / viewport.scale
            };
        });

        this.createHighlight(pageNumber, highlights, textContent);
        selection.removeAllRanges();
    }

    createHighlight(pageNumber, rects, text) {
        const highlight = {
            id: `highlight-${Date.now()}`,
            pageNumber,
            rects,
            text,
            color: this.currentColor,
            comment: ''
        };

        console.log('Creating new highlight:', highlight);
        this.highlights.push(highlight);
        this.renderHighlight(highlight);
        this.saveHighlights();
        this.showCommentForm(highlight);
    }

    renderHighlight(highlight) {
        const pageView = this.pdfViewer.getPageView(highlight.pageNumber - 1);
        if (!pageView) return;

        let annotationLayer = pageView.div.querySelector('.annotationLayer');
        if (!annotationLayer) {
            annotationLayer = document.createElement('div');
            annotationLayer.className = 'annotationLayer';
            pageView.div.appendChild(annotationLayer);
        }

        highlight.rects.forEach(rect => {
            const element = document.createElement('div');
            element.className = 'pdfViewer-highlight';
            element.dataset.highlightId = highlight.id;
            element.style.position = 'absolute';
            element.style.backgroundColor = `${highlight.color}80`; // 50% opacity
            element.style.left = `${rect.left}px`;
            element.style.top = `${rect.top}px`;
            element.style.width = `${rect.width}px`;
            element.style.height = `${rect.height}px`;
            
            element.addEventListener('click', () => this.showCommentForm(highlight));
            annotationLayer.appendChild(element);
            
            console.log('Rendered highlight element:', element);
        });
    }

    renderAllHighlights() {
        // Clear existing highlights
        document.querySelectorAll('.pdfViewer-highlight').forEach(el => el.remove());
        
        // Render all highlights
        this.highlights.forEach(highlight => this.renderHighlight(highlight));
    }

    showCommentForm(highlight) {
        this.selectedHighlight = highlight;
        if (this.commentForm) {
            this.commentForm.style.display = 'block';
            this.commentInput.value = highlight.comment || '';
            this.commentInput.focus();
        }
    }

    bindEvents() {
        // Re-render highlights when pages are rendered
        this.eventBus.on('pagerendered', () => {
            requestAnimationFrame(() => this.renderAllHighlights());
        });

        // Handle page changes
        this.eventBus.on('pagechanging', () => {
            requestAnimationFrame(() => this.renderAllHighlights());
        });

        // Save comment when form is submitted
        if (this.commentForm) {
            this.commentForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if (this.selectedHighlight) {
                    this.selectedHighlight.comment = this.commentInput.value;
                    this.saveHighlights();
                    this.commentForm.style.display = 'none';
                }
            });
        }

        // Load highlights when PDF is loaded
        window.addEventListener('pdfloaded', () => {
            console.log('PDF loaded, loading highlights');
            this.loadHighlights();
        });
    }

    saveHighlights() {
        if (this.pdfViewer.pdfDocument) {
            const documentFingerprint = this.pdfViewer.pdfDocument.fingerprints[0];
            localStorage.setItem(
                `pdfHighlights_${documentFingerprint}`,
                JSON.stringify(this.highlights)
            );
            console.log('Highlights saved:', this.highlights);
        }
    }

    loadHighlights() {
        if (this.pdfViewer.pdfDocument) {
            const documentFingerprint = this.pdfViewer.pdfDocument.fingerprints[0];
            const savedHighlights = localStorage.getItem(`pdfHighlights_${documentFingerprint}`);
            if (savedHighlights) {
                this.highlights = JSON.parse(savedHighlights);
                this.renderAllHighlights();
                console.log('Loaded highlights:', this.highlights);
            }
        }
    }
}

// Initialize the annotator when the PDF is loaded
window.addEventListener('pdfloaded', () => {
    if (!window.annotator) {
        window.annotator = new PDFAnnotator();
    }
});