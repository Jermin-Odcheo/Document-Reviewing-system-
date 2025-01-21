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

    // Get scroll offsets
    const viewerContainer = document.getElementById('viewerContainer');
    const scrollLeft = viewerContainer.scrollLeft;
    const scrollTop = viewerContainer.scrollTop;

    // Get page and textLayer positions
    const pageRect = page.getBoundingClientRect();
    const textLayerRect = targetTextLayer.getBoundingClientRect();

    // Create highlight elements
    const rects = range.getClientRects();
    for (let i = 0; i < rects.length; i++) {
        const rect = rects[i];

        // Create highlight element
        const highlight = document.createElement('div');
        highlight.className = 'pdf-highlight';

        // Calculate position relative to the page, accounting for scroll
        const x = (rect.left + scrollLeft - pageRect.left) / viewport.scale;
        const y = (rect.top + scrollTop - pageRect.top) / viewport.scale;
        const width = rect.width / viewport.scale;
        const height = rect.height / viewport.scale;

        // Position highlight
        highlight.style.left = `${x}px`;
        highlight.style.top = `${y}px`;
        highlight.style.width = `${width}px`;
        highlight.style.height = `${height}px`;
        highlight.style.backgroundColor = 'rgba(255, 255, 0, 0.3)';

        // Add to annotation layer
        let annotationLayer = page.querySelector('.annotationLayer');
        if (!annotationLayer) {
            annotationLayer = document.createElement('div');
            annotationLayer.className = 'annotationLayer';
            page.appendChild(annotationLayer);
        }
        annotationLayer.appendChild(highlight);
    }

    selection.removeAllRanges();
}

initializeViewer() {
    try {
        // Initialize link service
        this.linkService = new pdfjsViewer.PDFLinkService({
            eventBus: this.eventBus,
        });

        // Initialize PDF viewer with correct text layer settings
        this.pdfViewer = new pdfjsViewer.PDFViewer({
            container: this.viewerContainer,
            viewer: this.viewer,
            eventBus: this.eventBus,
            linkService: this.linkService,
            textLayerMode: 2, // Enable enhanced text layer
            renderInteractiveForms: false,
            enableScripting: false,
            removePageBorders: true, // Remove default borders
            renderer: 'canvas',
            l10n: pdfjsViewer.NullL10n,
            useOnlyCssZoom: false,
            maxCanvasPixels: 16777216,
            defaultViewport: {
                scale: 1,
            },
            scrollMode: 0,
            spreadMode: 0,
        });

        // Set up link service
        this.linkService.setViewer(this.pdfViewer);

        // Set initial zoom when document is loaded
        this.eventBus.on('pagesinit', () => {
            // Set to auto scale for better rendering
            this.pdfViewer.currentScaleValue = 'auto';
        });

        // Ensure text layer is rendered properly
        this.eventBus.on('textlayerrendered', (evt) => {
            const page = evt.source.textLayer.div.closest('.page');
            if (page) {
                // Ensure proper text layer positioning
                const textLayer = page.querySelector('.textLayer');
                if (textLayer) {
                    textLayer.style.transform = '';
                    textLayer.style.transformOrigin = '';
                }
            }
        });

        console.log('Viewer initialized successfully');
    } catch (error) {
        console.error('Error initializing viewer:', error);
        throw error;
    }
} 