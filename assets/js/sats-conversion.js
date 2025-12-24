/**
 * PirateHash Sats Conversion for WooCommerce Blocks
 */

(function() {
    'use strict';

    let btcPrice = null;
    let isProcessing = false;

    // Fetch BTC price from mempool API
    async function fetchBTCPrice() {
        try {
            const response = await fetch('https://mempool.space/api/v1/prices');
            const data = await response.json();
            btcPrice = data.USD;
            return btcPrice;
        } catch (error) {
            console.error('Failed to fetch BTC price:', error);
            btcPrice = 100000; // Fallback
            return btcPrice;
        }
    }

    // Convert USD to sats
    function usdToSats(usd) {
        if (!btcPrice || btcPrice <= 0) return 0;
        return Math.round((usd / btcPrice) * 100000000);
    }

    // Format sats with commas
    function formatSats(sats) {
        return sats.toLocaleString() + ' sats';
    }

    // Parse price from element text
    function parsePrice(text) {
        if (!text) return 0;
        // Remove currency symbols and parse
        const cleaned = text.replace(/[^0-9.,]/g, '').replace(',', '');
        return parseFloat(cleaned) || 0;
    }

    // Mark element as processed
    function markProcessed(element) {
        element.setAttribute('data-sats-processed', 'true');
    }

    // Check if element is processed
    function isProcessed(element) {
        return element.hasAttribute('data-sats-processed');
    }

    // Process all cart/checkout prices
    function processCartPrices() {
        if (!btcPrice || isProcessing) return;
        isProcessing = true;

        // Cart item subtotal (right side price)
        document.querySelectorAll('.wc-block-cart-item__total-price-and-sale-badge-wrapper').forEach(wrapper => {
            if (isProcessed(wrapper)) return;
            
            const priceEl = wrapper.querySelector('.wc-block-components-product-price__value');
            if (priceEl) {
                const price = parsePrice(priceEl.textContent);
                if (price > 0) {
                    const sats = usdToSats(price);
                    const satsDiv = document.createElement('div');
                    satsDiv.className = 'sats-converted price-sats';
                    satsDiv.textContent = formatSats(sats);
                    wrapper.insertBefore(satsDiv, wrapper.firstChild);
                    markProcessed(wrapper);
                }
            }
        });

        // Cart item unit price (left side under product name)
        document.querySelectorAll('.wc-block-components-product-metadata').forEach(meta => {
            const priceContainer = meta.closest('.wc-block-cart-item__product');
            if (!priceContainer) return;
            
            const priceEl = priceContainer.querySelector('.wc-block-components-product-price:not(.wc-block-cart-item__total-price-and-sale-badge-wrapper .wc-block-components-product-price)');
            if (priceEl && !isProcessed(priceEl)) {
                const valueEl = priceEl.querySelector('.wc-block-components-product-price__value');
                if (valueEl) {
                    const price = parsePrice(valueEl.textContent);
                    if (price > 0) {
                        const sats = usdToSats(price);
                        const satsDiv = document.createElement('div');
                        satsDiv.className = 'sats-converted price-sats';
                        satsDiv.textContent = formatSats(sats);
                        priceEl.insertBefore(satsDiv, priceEl.firstChild);
                        markProcessed(priceEl);
                    }
                }
            }
        });

        // Estimated total
        document.querySelectorAll('.wc-block-components-totals-footer-item').forEach(totalItem => {
            if (isProcessed(totalItem)) return;
            
            const valueEl = totalItem.querySelector('.wc-block-components-totals-item__value');
            if (valueEl) {
                const price = parsePrice(valueEl.textContent);
                if (price > 0) {
                    const sats = usdToSats(price);
                    const satsDiv = document.createElement('div');
                    satsDiv.className = 'sats-converted price-sats total-sats';
                    satsDiv.textContent = formatSats(sats);
                    
                    // Insert inside the value element, before its content
                    valueEl.insertBefore(satsDiv, valueEl.firstChild);
                    markProcessed(totalItem);
                }
            }
        });

        isProcessing = false;
    }

    // Clear processed markers (for cart updates)
    function clearProcessedMarkers() {
        document.querySelectorAll('[data-sats-processed]').forEach(el => {
            el.removeAttribute('data-sats-processed');
        });
        document.querySelectorAll('.sats-converted').forEach(el => {
            el.remove();
        });
    }

    // Initialize
    async function init() {
        await fetchBTCPrice();
        processCartPrices();

        // Watch for DOM changes (cart updates)
        const observer = new MutationObserver((mutations) => {
            let shouldProcess = false;
            
            mutations.forEach(mutation => {
                // Only process if actual content changed, not our additions
                if (mutation.target.classList && mutation.target.classList.contains('sats-converted')) {
                    return;
                }
                if (mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1 && !node.classList.contains('sats-converted')) {
                            shouldProcess = true;
                        }
                    });
                }
            });
            
            if (shouldProcess) {
                clearTimeout(window.satsProcessTimeout);
                window.satsProcessTimeout = setTimeout(() => {
                    clearProcessedMarkers();
                    processCartPrices();
                }, 300);
            }
        });

        // Observe cart/checkout containers
        const containers = document.querySelectorAll('.wc-block-cart, .wc-block-checkout, .wp-block-woocommerce-cart, .wp-block-woocommerce-checkout');
        containers.forEach(container => {
            observer.observe(container, { 
                childList: true, 
                subtree: true
            });
        });
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
