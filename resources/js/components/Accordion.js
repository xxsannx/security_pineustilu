/**
 * Accordion Component
 * Handles expandable/collapsible content sections
 */

import { forceReflow, qsa } from '../utils/dom.js';

export class Accordion {
    /**
     * @param {Object} options
     * @param {string} options.toggleSelector - Selector for toggle buttons
     * @param {number} options.animationDuration - Animation duration in ms
     */
    constructor(options = {}) {
        this.toggleSelector = options.toggleSelector || '.accordion-toggle, .ft-toggle, [data-accordion-header]';
        this.animationDuration = options.animationDuration || 320;
        this.toggles = [];
    }

    /**
     * Initialize accordion
     */
    init() {
        this.toggles = qsa(this.toggleSelector);
        this.bindEvents();
        this.initializePanels();
        return this;
    }

    /**
     * Bind click events
     */
    bindEvents() {
        this.toggles.forEach(btn => {
            btn.addEventListener('click', () => this.handleToggle(btn));
        });
    }

    /**
     * Initialize panel states - ensure closed panels are properly hidden
     */
    initializePanels() {
        this.toggles.forEach(btn => {
            const targetId = btn.dataset.target || btn.getAttribute('aria-controls');
            const panel = targetId ? document.getElementById(targetId) : btn.nextElementSibling;
            
            if (panel && btn.getAttribute('aria-expanded') !== 'true') {
                // Store original margin for later use
                const computedStyle = window.getComputedStyle(panel);
                panel.dataset.originalMargin = computedStyle.marginTop;
                
                // Hide panel properly
                panel.style.maxHeight = '0';
                panel.style.opacity = '0';
                panel.style.overflow = 'hidden';
                panel.style.marginTop = '0';
                panel.style.display = 'none';
            }
        });
    }

    /**
     * Handle toggle click
     * @param {Element} btn 
     */
    handleToggle(btn) {
        const targetId = btn.dataset.target || btn.getAttribute('aria-controls');
        const panel = targetId ? document.getElementById(targetId) : btn.nextElementSibling;
        const icon = btn.querySelector('.ft-icon, [data-accordion-icon]');
        const isExpanded = btn.getAttribute('aria-expanded') === 'true';

        btn.setAttribute('aria-expanded', (!isExpanded).toString());

        if (isExpanded) {
            this.closePanel(panel, icon);
        } else {
            this.openPanel(panel, icon);
        }
    }

    /**
     * Open a panel with animation
     * @param {Element} panel 
     * @param {Element} icon 
     */
    openPanel(panel, icon) {
        if (!panel) return;

        // Show panel and prepare for animation
        panel.classList.remove('hidden');
        panel.style.display = 'block';
        panel.style.overflow = 'hidden';
        
        // Get the original margin (default to 0.5rem if not set)
        const originalMargin = panel.dataset.originalMargin || '0.5rem';
        
        // Reset to calculate proper scrollHeight
        panel.style.maxHeight = 'auto';
        panel.style.marginTop = originalMargin;
        const height = panel.scrollHeight;
        
        // Set initial state for animation
        panel.style.maxHeight = '0';
        panel.style.opacity = '0';
        panel.style.marginTop = '0';
        
        forceReflow(panel);

        // Animate to open state
        panel.style.transition = `max-height ${this.animationDuration}ms ease-out, opacity ${this.animationDuration}ms ease-out, margin-top ${this.animationDuration}ms ease-out`;
        panel.style.maxHeight = height + 'px';
        panel.style.opacity = '1';
        panel.style.marginTop = originalMargin;

        if (icon) icon.style.transform = 'rotate(180deg)';

        panel.dataset.open = 'true';
        panel.setAttribute('aria-hidden', 'false');

        // After animation, allow content to grow naturally
        setTimeout(() => {
            if (panel.dataset.open === 'true') {
                panel.style.maxHeight = 'none';
                panel.style.overflow = 'visible';
            }
        }, this.animationDuration);
    }

    /**
     * Close a panel with animation
     * @param {Element} panel 
     * @param {Element} icon 
     */
    closePanel(panel, icon) {
        if (!panel) return;

        // Set explicit height before animating
        panel.style.overflow = 'hidden';
        panel.style.maxHeight = panel.scrollHeight + 'px';
        
        forceReflow(panel);

        // Animate to closed state
        panel.style.transition = `max-height ${this.animationDuration}ms ease-in, opacity ${this.animationDuration}ms ease-in, margin-top ${this.animationDuration}ms ease-in`;
        panel.style.maxHeight = '0';
        panel.style.opacity = '0';
        panel.style.marginTop = '0';

        if (icon) icon.style.transform = 'rotate(0deg)';

        panel.dataset.open = 'false';
        panel.setAttribute('aria-hidden', 'true');

        // Hide element after animation completes
        setTimeout(() => {
            if (panel.dataset.open === 'false') {
                panel.style.display = 'none';
                panel.classList.add('hidden');
            }
        }, this.animationDuration);
    }
}

/**
 * Simple toggle function for inline use
 * @param {string} id 
 */
export function toggleAccordion(id) {
    const accordion = document.getElementById('accordion-' + id);
    const icon = document.getElementById('icon-' + id);

    if (accordion && icon) {
        if (accordion.classList.contains('hidden')) {
            accordion.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            accordion.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
}

export default Accordion;
