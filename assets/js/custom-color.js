document.addEventListener('DOMContentLoaded', function() {
    const colorThief = new ColorThief();

    // Function to apply color extraction logic
    function applyColorLogic(item) {
        const img = item.querySelector('img');
        if (img.complete && img.naturalHeight !== 0) {
            extractAndSetColors();
        } else {
            img.addEventListener('load', extractAndSetColors);
        }

        function extractAndSetColors() {
            try {
                const dominantColor = colorThief.getColor(img);
                const colorRgb = `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`;
                item.addEventListener('mouseenter', function() {
                    item.style.backgroundColor = colorRgb;
                });
                item.addEventListener('mouseleave', function() {
                    item.style.backgroundColor = '';
                });
            } catch (error) {
                console.error('Error extracting color:', error);
            }
        }
    }

    // Initial application of the logic
    const initialItems = document.querySelectorAll('.portfolio-list-item');
    initialItems.forEach(applyColorLogic);

    // MutationObserver to handle AJAX-loaded content
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1 && node.matches('.portfolio-list-item')) { // Check if the added node is an element and has the class
                    applyColorLogic(node);
                }
            });
        });
    });

    // Configuration of the observer:
    const config = { childList: true, subtree: true };

    // Start observing
    observer.observe(document.body, config);
});