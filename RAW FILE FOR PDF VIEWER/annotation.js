
    document.addEventListener('DOMContentLoaded', function() {
      const DEFAULT_URL = 'pdf.pdf'; // Path to your PDF

      // Check that PDF.js is loaded
      if (!pdfjsLib.getDocument || !pdfjsViewer.PDFViewer) {
        alert("PDF.js library not found or not loaded correctly.");
        return;
      }

      // PDF.js worker path
      pdfjsLib.GlobalWorkerOptions.workerSrc = 'pdf.worker.js'; 

      // References to UI elements
      const zoominbutton = document.getElementById("pdfZoominbutton");
      const zoomoutbutton = document.getElementById("pdfZoomOutbutton");
      const rotatebutton = document.getElementById("pdfRotatebutton");

      const highlightbutton = document.getElementById("pdfHighlightbutton");
      const colorPicker = document.getElementById("highlightColorPicker");
      const saveHighlightsButton = document.getElementById("saveHighlightsbutton");
      const clearHighlightsButton = document.getElementById("clearHighlightsbutton");

      const container = document.getElementById('viewerContainer');
      const viewer = document.getElementById('pdfViewer');
      const input = document.getElementById("pdfInput");

      const commentPanel = document.getElementById("commentPanel");

      const commentPopup = document.getElementById("commentPopup");
      const commentTextArea = document.getElementById("commentText");
      const addCommentBtn = document.getElementById("addCommentBtn");
      const cancelCommentBtn = document.getElementById("cancelCommentBtn");

      let isHighlightMode = false;
      let highlights = []; // store highlight objects

      const pdfLinkService = new pdfjsViewer.PDFLinkService();
      const pdfViewerInstance = new pdfjsViewer.PDFViewer({
        container: container,
        viewer: viewer,
        linkService: pdfLinkService,
        textLayerMode: 1,
      });
      pdfLinkService.setViewer(pdfViewerInstance);

      // Load PDF
      pdfjsLib.getDocument({ url: DEFAULT_URL }).promise.then(pdfDoc => {
        pdfViewerInstance.setDocument(pdfDoc);
        pdfLinkService.setDocument(pdfDoc, null);
      }).catch(err => {
        console.error("Error loading PDF:", err);
        alert("Could not load PDF.");
      });

      // Once pages init
      pdfViewerInstance.eventBus.on('pagesinit', function() {
        pdfViewerInstance.currentScaleValue = 'auto';
        updatePageControls();
        input.value = pdfViewerInstance.currentPageNumber;
      });

      // Re-render highlights on text layer or scale changes
      pdfViewerInstance.eventBus.on('textlayerrendered', evt => {
        loadHighlightsForPage(evt.pageNumber);
      });
      pdfViewerInstance.eventBus.on('scalechanging', () => {
        loadHighlightsForPage(pdfViewerInstance.currentPageNumber);
      });
      pdfViewerInstance.eventBus.on('rotating', () => {
        loadHighlightsForPage(pdfViewerInstance.currentPageNumber);
      });

      // Page Navigation
      const nextPageButton = document.getElementById("next-page");
      const prevPageButton = document.getElementById("prev-page");

      nextPageButton.onclick = () => {
        if (pdfViewerInstance.currentPageNumber < pdfViewerInstance.pagesCount) {
          pdfViewerInstance.currentPageNumber++;
          input.value = pdfViewerInstance.currentPageNumber;
          updatePageControls();
        }
      };
      prevPageButton.onclick = () => {
        if (pdfViewerInstance.currentPageNumber > 1) {
          pdfViewerInstance.currentPageNumber--;
          input.value = pdfViewerInstance.currentPageNumber;
          updatePageControls();
        }
      };
      input.addEventListener("keyup", (event) => {
        if (event.key === 'Enter') {
          let val = parseInt(input.value, 10);
          if (!val) return;
          if (val > pdfViewerInstance.pagesCount) val = pdfViewerInstance.pagesCount;
          pdfViewerInstance.currentPageNumber = val;
          updatePageControls();
        }
      });
      container.onscroll = () => {
        input.value = pdfViewerInstance.currentPageNumber;
        updatePageControls();
      };
      function updatePageControls() {
        prevPageButton.disabled = (pdfViewerInstance.currentPageNumber <= 1);
        nextPageButton.disabled = (pdfViewerInstance.currentPageNumber >= pdfViewerInstance.pagesCount);
      }

      // Zoom
      const DEFAULT_SCALE_STEP = 0.15;
      zoominbutton.onclick = () => {
        pdfViewerInstance.currentScaleValue = pdfViewerInstance.currentScale + DEFAULT_SCALE_STEP;
      };
      zoomoutbutton.onclick = () => {
        const newScale = pdfViewerInstance.currentScale - DEFAULT_SCALE_STEP;
        if (newScale > 0) {
          pdfViewerInstance.currentScaleValue = newScale;
        }
      };

      // Rotate
      rotatebutton.onclick = () => {
        let rotateVal = pdfViewerInstance.pagesRotation + 90;
        if (rotateVal >= 360) rotateVal = 0;
        pdfViewerInstance.pagesRotation = rotateVal;
      };

      // Toggle highlight mode
      highlightbutton.onclick = () => {
        isHighlightMode = !isHighlightMode;
        if (isHighlightMode) {
          highlightbutton.classList.add('active');
          highlightbutton.setAttribute('aria-pressed', 'true');
          highlightbutton.innerHTML = '<i class="fas fa-marker"></i> Highlight (Active)';
          document.body.classList.add('highlight-mode');
        } else {
          highlightbutton.classList.remove('active');
          highlightbutton.setAttribute('aria-pressed', 'false');
          highlightbutton.innerHTML = '<i class="fas fa-marker"></i> Highlight';
          document.body.classList.remove('highlight-mode');
        }
      };

      // Floating popup logic
      function showCommentPopup(x, y) {
        commentPopup.style.left = x + 'px';
        commentPopup.style.top = y + 'px';
        commentPopup.style.display = 'block';
        commentTextArea.value = '';
        commentTextArea.focus();
      }
      function hideCommentPopup() {
        commentPopup.style.display = 'none';
      }

      cancelCommentBtn.onclick = () => {
        hideCommentPopup();
        window.getSelection().removeAllRanges();
      };

      // On mouseup, if in highlight mode, show the floating comment popup
      container.addEventListener('mouseup', function(event) {
        if (!isHighlightMode) return;

        const selection = window.getSelection();
        if (selection && !selection.isCollapsed) {
          const range = selection.getRangeAt(0);
          const rects = range.getClientRects();

          if (rects.length > 0) {
            // Just position the popup at the first rect for simplicity
            const firstRect = rects[0];
            const containerRect = container.getBoundingClientRect();

            // Calculate x,y relative to container
            const popupX = (firstRect.left - containerRect.left) + window.scrollX;
            const popupY = (firstRect.top - containerRect.top) + window.scrollY - 80; 
            // shift up by 80 so it's above the selection

            showCommentPopup(popupX, popupY);

            // If user clicks "Add," create the highlight(s)
            addCommentBtn.onclick = function() {
              const userComment = commentTextArea.value.trim();
              if (userComment !== '') {
                for (let i = 0; i < rects.length; i++) {
                  createHighlightFromRect(rects[i], pdfViewerInstance.currentPageNumber, userComment);
                }
              }
              selection.removeAllRanges();
              hideCommentPopup();
            };
          }
        }
      });

      // Create highlight object + re-render
      function createHighlightFromRect(rect, pageNumber, comment) {
        const pageView = pdfViewerInstance.getPageView(pageNumber - 1);
        if (!pageView || !pageView.viewport) return;

        const viewport = pageView.viewport;
        const textLayerDiv = pageView.div.querySelector('.textLayer');
        if (!textLayerDiv) return;

        const textLayerRect = textLayerDiv.getBoundingClientRect();
        const relativeLeft = rect.left - textLayerRect.left;
        const relativeTop = rect.top - textLayerRect.top;

        const pdfX = relativeLeft / viewport.scale;
        const pdfY = (viewport.height - (relativeTop + rect.height)) / viewport.scale;
        const pdfWidth = rect.width / viewport.scale;
        const pdfHeight = rect.height / viewport.scale;

        // Quick checks
        if (pdfX < 0 || pdfY < 0 || pdfWidth <= 0 || pdfHeight <= 0) {
          console.warn("Skipping invalid highlight dimension.");
          return;
        }

        const highlight = {
          id: Date.now().toString(),
          page: pageNumber,
          x: pdfX,
          y: pdfY,
          width: pdfWidth,
          height: pdfHeight,
          color: colorPicker.value,
          comment: comment,
          element: null
        };
        highlights.push(highlight);

        // Render highlight + side comment
        loadHighlightsForPage(pageNumber);
        renderCommentInSidePanel(highlight);
      }

      // Render highlight overlay on page
      function renderHighlight(h) {
        const pageView = pdfViewerInstance.getPageView(h.page - 1);
        if (!pageView || !pageView.viewport) return;

        const viewport = pageView.viewport;
        const textLayerDiv = pageView.div.querySelector('.textLayer');
        if (!textLayerDiv) return;

        // Convert PDF coords -> screen coords
        const [sx1, sy1] = viewport.convertToViewportPoint(h.x, h.y);
        const [sx2, sy2] = viewport.convertToViewportPoint(h.x + h.width, h.y + h.height);

        const left = Math.min(sx1, sx2);
        const top = Math.min(sy1, sy2);
        const width = Math.abs(sx2 - sx1);
        const height = Math.abs(sy2 - sy1);

        const highlightDiv = document.createElement('div');
        highlightDiv.classList.add('pdf-highlight');
        highlightDiv.style.left = left + 'px';
        highlightDiv.style.top = top + 'px';
        highlightDiv.style.width = width + 'px';
        highlightDiv.style.height = height + 'px';
        highlightDiv.style.backgroundColor = h.color;

        // Removing highlight on click
        highlightDiv.onclick = function(e) {
          e.stopPropagation();
          if (confirm("Do you want to remove this highlight?")) {
            highlightDiv.remove();
            highlights = highlights.filter(item => item.id !== h.id);

            // Remove side comment
            const sideComment = document.querySelector(`[data-highlight-id='${h.id}']`);
            if (sideComment) sideComment.remove();
          }
        };

        textLayerDiv.appendChild(highlightDiv);
        h.element = highlightDiv;
      }

      // Re-draw highlights for a page
      function loadHighlightsForPage(pageNumber) {
        const pageView = pdfViewerInstance.getPageView(pageNumber - 1);
        if (!pageView) return;
        const textLayerDiv = pageView.div.querySelector('.textLayer');
        if (!textLayerDiv) return;

        // Remove old highlight divs to avoid duplicates
        const oldHL = textLayerDiv.querySelectorAll('.pdf-highlight');
        oldHL.forEach(div => div.remove());

        // Render only highlights for this page
        highlights.filter(h => h.page === pageNumber).forEach(h => {
          h.element = null; // reset
          renderHighlight(h);
        });
      }

      // Add new comment entry to the side panel
      function renderCommentInSidePanel(highlight) {
        const entry = document.createElement('div');
        entry.className = 'commentEntry';
        entry.dataset.highlightId = highlight.id;

        // color indicator
        const colorIndicator = document.createElement('span');
        colorIndicator.className = 'commentColorIndicator';
        colorIndicator.style.backgroundColor = highlight.color;

        // page label
        const pageLabel = document.createElement('span');
        pageLabel.className = 'commentPageLabel';
        pageLabel.textContent = `Page ${highlight.page}: `;

        // comment text
        const commentText = document.createElement('p');
        commentText.textContent = highlight.comment;

        // click => jump to that page
        entry.addEventListener('click', () => {
          pdfViewerInstance.currentPageNumber = highlight.page;
        });

        entry.appendChild(colorIndicator);
        entry.appendChild(pageLabel);
        entry.appendChild(commentText);

        commentPanel.appendChild(entry);
      }

      // ============ Save Highlights (draw rectangles) ============
      saveHighlightsButton.onclick = async function() {
        try {
          if (highlights.length === 0) {
            alert("No highlights to save.");
            return;
          }

          // Fetch original PDF
          const pdfBytes = await fetch(DEFAULT_URL).then(r => r.arrayBuffer());
          // Load with PDF-lib
          const pdfLibDoc = await PDFLib.PDFDocument.load(pdfBytes);

          // For each highlight, draw rectangle
          highlights.forEach((h, idx) => {
            const page = pdfLibDoc.getPage(h.page - 1);
            const rotation = page.getRotation().angle;
            const width = page.getWidth();
            const height = page.getHeight();

            const highlightColor = PDFLib.rgb(
              parseInt(h.color.slice(1,3), 16) / 255,
              parseInt(h.color.slice(3,5), 16) / 255,
              parseInt(h.color.slice(5,7), 16) / 255
            );

            let pdfX = h.x;
            let pdfY = h.y;
            let rectWidth = h.width;
            let rectHeight = h.height;

            // Handle rotation
            switch(rotation) {
              case 90:
                pdfX = h.y;
                pdfY = width - (h.x + h.width);
                rectWidth = h.height;
                rectHeight = h.width;
                break;
              case 180:
                pdfX = width - (h.x + h.width);
                pdfY = height - (h.y + h.height);
                break;
              case 270:
                pdfX = height - (h.y + h.height);
                pdfY = h.x;
                rectWidth = h.height;
                rectHeight = h.width;
                break;
              default:
                // no rotation
                break;
            }

            page.drawRectangle({
              x: pdfX,
              y: pdfY,
              width: rectWidth,
              height: rectHeight,
              color: highlightColor,
              opacity: 0.4
            });
          });

          // Save new PDF
          const modifiedPdfBytes = await pdfLibDoc.save();
          downloadFile(modifiedPdfBytes, "highlighted.pdf", "application/pdf");
          alert("Highlights saved successfully!");
        } catch (err) {
          console.error("Error saving highlights:", err);
          alert("An error occurred while saving the PDF.");
        }
      };

      // Helper to download file
      function downloadFile(data, filename, type) {
        const blob = new Blob([data], { type });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
      }

      // ============ Clear All Highlights ============
      clearHighlightsButton.onclick = function() {
        if (confirm("Are you sure you want to clear all highlights?")) {
          // Remove highlight divs
          const existingHL = pdfViewerInstance.container.querySelectorAll('.pdf-highlight');
          existingHL.forEach(div => div.remove());
          // Clear array
          highlights = [];
          // Clear side panel
          while (commentPanel.lastChild && commentPanel.lastChild.classList.contains('commentEntry')) {
            commentPanel.removeChild(commentPanel.lastChild);
          }
        }
      };
    });
