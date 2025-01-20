class PDFAnnotator {
    constructor() {
        this.isHighlighting = false;
        this.highlights = [];
        this.selectedHighlight = null;
        this.currentColor = '#ffeb3b';
        this.pdfViewer = window.pdfViewer.pdfViewer;
        
        // Get UI elements
        this.commentForm = document.getElementById('commentForm');
        this.commentInput = document.getElementById('commentInput');
        this.commentList = document.getElementById('commentList');
        
        this.init();
        this.bindEvents();
    }

    init() {
        this.setupToolbar();
        this.setupColorPicker();
        this.setupCommentFormListeners();
    }

    setupToolbar() {
        const highlighterBtn = document.getElementById('highlighterTool');
        if (highlighterBtn) {
            highlighterBtn.addEventListener('click', () => this.toggleHighlighting());
        }

        const clearBtn = document.getElementById('clearAnnotations');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearAnnotations());
        }
    }

    setupColorPicker() {
        const colorPicker = document.getElementById('colorPicker');
        colorPicker.addEventListener('input', (e) => {
            this.currentColor = e.target.value;
        });
    }

    setupCommentFormListeners() {
        document.getElementById('saveComment').addEventListener('click', () => {
            this.saveComment();
        });

        document.getElementById('cancelComment').addEventListener('click', () => {
            this.hideCommentForm();
        });
    }

    handleHighlight(event) {
        if (!this.isHighlighting) return;

        const selection = window.getSelection();
        if (!selection.rangeCount || selection.isCollapsed) return;

        const range = selection.getRangeAt(0);
        if (!range.toString().trim()) return;

        const textLayer = range.commonAncestorContainer.closest('.textLayer');
        if (!textLayer) return;

        const page = textLayer.closest('.page');
        if (!page) return;

        const pageNumber = parseInt(page.dataset.pageNumber);
        const pageView = this.pdfViewer.getPageView(pageNumber - 1);
        const viewport = pageView.viewport;
        const textLayerRect = textLayer.getBoundingClientRect();

        const rects = range.getClientRects();
        const highlightRects = Array.from(rects).map(rect => ({
            left: (rect.left - textLayerRect.left) / viewport.scale,
            top: (rect.top - textLayerRect.top) / viewport.scale,
            width: rect.width / viewport.scale,
            height: rect.height / viewport.scale
        }));

        const highlight = {
            id: Date.now(),
            pageNumber,
            rects: highlightRects,
            color: this.currentColor,
            text: range.toString(),
            comment: ''
        };

        this.highlights.push(highlight);
        this.renderHighlight(highlight);
        this.showCommentForm(highlight);
        this.updateCommentsList();

        selection.removeAllRanges();
    }

    renderHighlight(highlight) {
        const pageView = this.pdfViewer.getPageView(highlight.pageNumber - 1);
        if (!pageView) return;

        // Create annotation layer if it doesn't exist
        let annotationLayer = pageView.div.querySelector('.annotationLayer');
        if (!annotationLayer) {
            annotationLayer = document.createElement('div');
            annotationLayer.className = 'annotationLayer';
            pageView.div.appendChild(annotationLayer);
        }

        highlight.rects.forEach(rect => {
            const highlightEl = document.createElement('div');
            highlightEl.className = 'pdf-highlight';
            highlightEl.dataset.highlightId = highlight.id;
            highlightEl.style.left = `${rect.left}px`;
            highlightEl.style.top = `${rect.top}px`;
            highlightEl.style.width = `${rect.width}px`;
            highlightEl.style.height = `${rect.height}px`;
            highlightEl.style.backgroundColor = highlight.color + '80'; // 50% opacity
            
            // Add click handler to edit comment
            highlightEl.addEventListener('click', () => {
                this.showCommentForm(highlight);
            });
            
            annotationLayer.appendChild(highlightEl);
        });
    }

    renderAllHighlights() {
        // Clear existing highlights
        document.querySelectorAll('.pdf-highlight').forEach(el => el.remove());
        
        // Render all highlights
        this.highlights.forEach(highlight => {
            this.renderHighlight(highlight);
        });
    }

    toggleHighlighting() {
        this.isHighlighting = !this.isHighlighting;
        const highlighterButton = document.getElementById('highlighterTool');
        highlighterButton.classList.toggle('active', this.isHighlighting);

        const viewerContainer = document.getElementById('viewerContainer');
        if (this.isHighlighting) {
            viewerContainer.style.cursor = 'text';
            viewerContainer.addEventListener('mouseup', this.handleHighlight.bind(this));
        } else {
            viewerContainer.style.cursor = 'default';
            viewerContainer.removeEventListener('mouseup', this.handleHighlight.bind(this));
        }
    }

    showCommentForm(highlight) {
        this.selectedHighlight = highlight;
        this.commentForm.style.display = 'block';
        this.commentInput.value = highlight.comment || '';
        this.commentInput.focus();
    }

    hideCommentForm() {
        this.commentForm.style.display = 'none';
        this.selectedHighlight = null;
        this.commentInput.value = '';
    }

    saveComment() {
        if (this.selectedHighlight) {
            this.selectedHighlight.comment = this.commentInput.value.trim();
            this.hideCommentForm();
            this.updateCommentsList();
        }
    }

    updateCommentsList() {
        this.commentList.innerHTML = '';
        
        this.highlights.forEach((highlight, index) => {
            const commentDiv = document.createElement('div');
            commentDiv.className = 'comment-item';
            commentDiv.innerHTML = `
                <div class="comment-header">
                    <span class="comment-number">Highlight ${index + 1}</span>
                    <div class="comment-color" style="background-color: ${highlight.color}"></div>
                    <button class="delete-comment" data-index="${index}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="comment-text">${highlight.comment || '<i>No comment</i>'}</div>
                <div class="highlight-text">"${highlight.text}"</div>
            `;
            
            // Add click handler to edit comment
            commentDiv.addEventListener('click', () => {
                this.showCommentForm(highlight);
            });
            
            this.commentList.appendChild(commentDiv);
        });
    }

    clearAnnotations() {
        this.highlights = [];
        document.querySelectorAll('.pdf-highlight').forEach(el => el.remove());
        this.updateCommentsList();
        this.hideCommentForm();
    }

    bindEvents() {
        // Re-render highlights when pages are rendered
        this.pdfViewer.eventBus.on('pagerendered', (evt) => {
            this.renderAllHighlights();
        });

        // Handle page changes
        this.pdfViewer.eventBus.on('pagechanging', () => {
            // Wait for the page to render before re-rendering highlights
            setTimeout(() => this.renderAllHighlights(), 100);
        });

        // Add click handler for delete buttons
        this.commentList.addEventListener('click', (e) => {
            if (e.target.closest('.delete-comment')) {
                const index = parseInt(e.target.closest('.delete-comment').dataset.index);
                this.highlights.splice(index, 1);
                this.updateCommentsList();
                this.renderAllHighlights();
            }
        });
    }
}

// The event listeners will be set up when the PDF is loaded via script.js