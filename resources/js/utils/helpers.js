/**
 * General Helper Functions
 * Common utility functions used across the application
 */

/**
 * Debounce function
 * @param {Function} fn - Function to debounce
 * @param {number} waitMs - Wait time in milliseconds
 * @returns {Function}
 */
export function debounce(fn, waitMs) {
    let timeout = null;
    return function (...args) {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), waitMs);
    };
}

/**
 * Throttle function
 * @param {Function} fn - Function to throttle
 * @param {number} limit - Time limit in milliseconds
 * @returns {Function}
 */
export function throttle(fn, limit) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            fn.apply(this, args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
}

/**
 * Safe JSON parse with fallback
 * @param {string} value - JSON string to parse
 * @param {*} fallback - Fallback value if parsing fails
 * @returns {*}
 */
export function safeJsonParse(value, fallback) {
    if (typeof value !== 'string' || value.trim() === '') return fallback;
    try {
        const parsed = JSON.parse(value);
        return parsed == null ? fallback : parsed;
    } catch (err) {
        return fallback;
    }
}

/**
 * Format currency to IDR
 * @param {number} value - Value to format
 * @returns {string}
 */
export function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

/**
 * Parse price string (e.g., "98k" -> 98000)
 * @param {string} priceStr - Price string
 * @returns {number}
 */
export function parsePrice(priceStr) {
    const cleaned = priceStr.toLowerCase().replace(/[^0-9k]/g, '');
    if (cleaned.includes('k')) {
        return parseInt(cleaned.replace('k', '')) * 1000;
    }
    return parseInt(cleaned) || 0;
}

/**
 * Clamp number between min and max
 * @param {number} num 
 * @param {number} min 
 * @param {number} max 
 * @returns {number}
 */
export function clamp(num, min, max) {
    return Math.max(min, Math.min(max, num));
}

/**
 * Get touch distance for pinch zoom
 * @param {TouchList} touches 
 * @returns {number}
 */
export function getTouchDistance(touches) {
    return Math.hypot(
        touches[0].clientX - touches[1].clientX,
        touches[0].clientY - touches[1].clientY
    );
}

/**
 * Sanitize string - remove whitespace and uppercase
 * @param {string} value 
 * @returns {string}
 */
export function sanitize(value) {
    return value.replace(/\s+/g, '').toUpperCase();
}

/**
 * Validate redeem code format
 * @param {string} code 
 * @returns {boolean}
 */
export function isValidRedeemCode(code) {
    return /^[A-Z0-9-]{6,32}$/.test(code);
}
