/**
 * Lightbox Component
 * Image lightbox/zoom functionality
 */

import { qsa } from '../utils/dom.js';

export class Lightbox {
    constructor(options = {}) {
        this.selector = options.selector || 'img[data-lightbox]';
        this.rootSelector = options.root || 'main';
        this.overlay = null;
        this.image = null;
    }

    /**
     * Initialize lightbox
     */
    init() {
        this.createOverlay();
        this.bindImages();
        return this;
    }

    /**
     * Create overlay element
     */
    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'lightbox-overlay';
        this.overlay.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            cursor: zoom-out;
        `;

        this.image = document.createElement('img');
        this.image.style.cssText = `
            max-width: 92vw;
            max-height: 92vh;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            border-radius: 12px;
        `;

        this.overlay.appendChild(this.image);
        document.body.appendChild(this.overlay);

        // Close on overlay click
        this.overlay.addEventListener('click', () => this.close());

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
        });
    }

    /**
     * Bind images for lightbox
     */
    bindImages() {
        const root = document.querySelector(this.rootSelector);
        if (!root) return;

        const images = qsa(this.selector, root);
        
        // If no explicit lightbox images, bind all images in root
        const targetImages = images.length ? images : qsa('img', root);

        targetImages.forEach(img => {
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', () => this.open(img.src, img.alt));
        });
    }

    /**
     * Open lightbox with image
     * @param {string} src 
     * @param {string} alt 
     */
    open(src, alt = '') {
        this.image.src = src;
        this.image.alt = alt;
        this.overlay.style.display = 'flex';
    }

    /**
     * Close lightbox
     */
    close() {
        this.overlay.style.display = 'none';
        this.image.src = '';
    }
}

export default Lightbox;
