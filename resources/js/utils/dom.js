/**
 * DOM Utility Functions
 * Common DOM manipulation helpers
 */

/**
 * Wait for DOM ready
 * @param {Function} fn - Callback function
 */
export function onReady(fn) {
    if (document.readyState !== 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

/**
 * Query selector shorthand
 * @param {string} selector 
 * @param {Element} root 
 * @returns {Element|null}
 */
export function qs(selector, root = document) {
    return root.querySelector(selector);
}

/**
 * Query selector all shorthand
 * @param {string} selector 
 * @param {Element} root 
 * @returns {Element[]}
 */
export function qsa(selector, root = document) {
    return Array.from(root.querySelectorAll(selector));
}

/**
 * Force browser reflow
 * @param {HTMLElement} element
 */
export function forceReflow(element) {
    return element.offsetHeight;
}

/**
 * Add event listener to multiple elements
 * @param {Element[]} elements 
 * @param {string} event 
 * @param {Function} handler 
 */
export function addEventToAll(elements, event, handler) {
    elements.forEach(el => el.addEventListener(event, handler));
}

/**
 * Toggle class on element
 * @param {Element} element 
 * @param {string} className 
 * @param {boolean} force 
 */
export function toggleClass(element, className, force) {
    if (element) {
        element.classList.toggle(className, force);
    }
}

/**
 * Safely get element by ID
 * @param {string} id 
 * @returns {Element|null}
 */
export function getById(id) {
    return document.getElementById(id);
}
