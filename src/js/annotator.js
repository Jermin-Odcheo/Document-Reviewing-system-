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
        this.commentSidebar = document.getElementById('commentsPanel');
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
        const viewerContainer = document.getElementById('viewerContainer');
        
        if (this.isHighlighting) {
            console.log('Highlighting mode enabled');
            highlighterButton.classList.add('active');
            viewerContainer.classList.add('highlighting');
            document.addEventListener('mouseup', this.handleSelectionBound);
            
            // Add visual feedback when starting to select
            document.addEventListener('mousedown', () => {
                if (this.isHighlighting) {
                    viewerContainer.style.cursor = 'crosshair';
                }
            });
            
            // Reset cursor after selection
            document.addEventListener('mouseup', () => {
                if (this.isHighlighting) {
                    viewerContainer.style.cursor = 'url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" style="font-size: 16px;"><text y="16">🖍️</text></svg>\'), auto';
                }
            });
        } else {
            console.log('Highlighting mode disabled');
            highlighterButton.classList.remove('active');
            viewerContainer.classList.remove('highlighting');
            viewerContainer.style.cursor = 'auto';
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

        if (!textLayer) return;

        const page = textLayer.closest('.page');
        if (!page) return;

        const pageNumber = parseInt(page.dataset.pageNumber);
        const pageView = this.pdfViewer.getPageView(pageNumber - 1);
        if (!pageView) return;

        const viewport = pageView.viewport;
        const textLayerRect = textLayer.getBoundingClientRect();
        const pageRect = page.getBoundingClientRect();
        const selectionRects = Array.from(range.getClientRects());

        // Calculate scroll offset
        const scrollLeft = this.viewerContainer.scrollLeft;
        const scrollTop = this.viewerContainer.scrollTop;

        const highlights = selectionRects.map(rect => {
            // Calculate position relative to the page
            const left = (rect.left + scrollLeft - pageRect.left) / viewport.scale;
            const top = (rect.top + scrollTop - pageRect.top) / viewport.scale;
            
            return {
                left: left,
                top: top,
                width: rect.width / viewport.scale,
                height: rect.height / viewport.scale
            };
        });

        this.createHighlight(pageNumber, highlights, textContent);
        selection.removeAllRanges();
    }

    createHighlight(pageNumber, rects, text) {
        const comment = prompt('Add a comment for this highlight:', '');
        if (comment === null) return;

        const highlight = {
            id: `highlight-${Date.now()}`,
            pageNumber,
            rects,
            text,
            color: this.currentColor,
            comment: comment
        };

        this.highlights.push(highlight);
        this.renderHighlight(highlight);
        this.updateCommentInSidebar(highlight);
        this.saveHighlights();
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
            
            // Apply positions
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
            // Make sure the form is visible
            this.commentForm.style.display = 'block';
            
            // Clear any previous comment
            this.commentInput.value = highlight.comment || '';
            
            // Focus the input
            this.commentInput.focus();
            
            // Add a prompt message if it's a new highlight
            if (!highlight.comment) {
                this.commentInput.placeholder = 'Add your comment for this highlight...';
            }
            
            // Update the sidebar to show the new highlight
            this.updateCommentInSidebar(highlight);
            
            // Scroll the comment into view in the sidebar
            const commentElement = this.commentSidebar.querySelector(`[data-highlight-id="${highlight.id}"]`);
            if (commentElement) {
                commentElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }

    updateCommentInSidebar(highlight) {
        if (!this.commentSidebar) return;

        // Find existing comment or create new one
        let commentElement = this.commentSidebar.querySelector(`[data-highlight-id="${highlight.id}"]`);
        
        if (!commentElement) {
            commentElement = document.createElement('div');
            commentElement.className = 'comment-item';
            commentElement.dataset.highlightId = highlight.id;
            this.commentSidebar.appendChild(commentElement);
        }

        // Update comment content
        commentElement.innerHTML = `
            <div class="comment-header">
                <div class="comment-color" style="background-color: ${highlight.color}"></div>
                <span class="comment-page">Page ${highlight.pageNumber}</span>
            </div>
            <div class="highlight-text">"${highlight.text}"</div>
            <div class="comment-body">
                <p>${highlight.comment}</p>
            </div>
            <div class="comment-actions">
                <button class="delete-comment" onclick="window.annotator.deleteHighlight('${highlight.id}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        `;
    }

    deleteHighlight(highlightId) {
        // Remove highlight from array
        this.highlights = this.highlights.filter(h => h.id !== highlightId);
        
        // Remove highlight elements from PDF
        document.querySelectorAll(`[data-highlight-id="${highlightId}"]`).forEach(el => el.remove());
        
        // Remove comment from sidebar
        const commentElement = this.commentSidebar.querySelector(`[data-highlight-id="${highlightId}"]`);
        if (commentElement) {
            commentElement.remove();
        }

        // Save updated highlights
        this.saveHighlights();
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

        // Handle comment form submission
        if (this.commentForm) {
            this.commentForm.addEventListener('submit', (e) => {
                e.preventDefault(); // Prevent form submission
                if (this.selectedHighlight) {
                    this.selectedHighlight.comment = this.commentInput.value;
                    this.updateCommentInSidebar(this.selectedHighlight);
                    this.saveHighlights();
                    this.commentInput.value = '';
                    this.commentInput.placeholder = 'Comment saved! Add another comment...';
                }
            });

            // Handle cancel button click
            const cancelButton = this.commentForm.querySelector('.cancel-comment');
            if (cancelButton) {
                cancelButton.addEventListener('click', () => {
                    if (this.selectedHighlight && !this.selectedHighlight.comment) {
                        this.deleteHighlight(this.selectedHighlight.id);
                    }
                    this.commentInput.value = '';
                    this.selectedHighlight = null;
                });
            }
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