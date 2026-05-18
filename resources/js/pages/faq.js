/**
 * FAQ Page JavaScript
 * Handles FAQ accordion and search functionality
 */

import { onReady, qsa } from '../utils/dom.js';

class Faq {
    constructor() {
        this.items = [];
        this.search = null;
        this.clearBtn = null;
    }

    init() {
        this.items = qsa('.faq-item');
        this.search = document.getElementById('faq-search');
        this.clearBtn = document.getElementById('faq-clear');

        this.setupAccordion();
        this.setupSearch();
        this.setupKeyboardNav();
    }

    setupAccordion() {
        this.items.forEach(item => {
            const btn = item.querySelector('.faq-q');
            const content = item.querySelector('.faq-a');

            if (!btn || !content) return;

            btn.addEventListener('click', () => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', !expanded);

                if (expanded) {
                    content.hidden = true;
                    item.setAttribute('aria-open', 'false');
                } else {
                    // Close others
                    this.items.forEach(i => {
                        if (i !== item) {
                            const otherContent = i.querySelector('.faq-a');
                            const otherBtn = i.querySelector('.faq-q');
                            if (otherContent) otherContent.hidden = true;
                            if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                            i.setAttribute('aria-open', 'false');
                        }
                    });

                    content.hidden = false;
                    item.setAttribute('aria-open', 'true');

                    // Scroll into view if needed
                    const rect = item.getBoundingClientRect();
                    if (rect.top < 80 || rect.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
                        item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    }

    setupSearch() {
        if (!this.search) return;

        this.search.addEventListener('input', (e) => this.filterFaq(e.target.value));

        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => {
                this.search.value = '';
                this.filterFaq('');
                this.search.focus();
            });
        }
    }

    setupKeyboardNav() {
        qsa('.faq-q').forEach(btn => {
            btn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    btn.click();
                }
            });
        });
    }

    filterFaq(query) {
        const term = (query || '').trim().toLowerCase();
        
        this.items.forEach(item => {
            const keywords = (item.dataset.keywords || '').toLowerCase();
            const text = item.textContent.toLowerCase();
            const match = !term || keywords.includes(term) || text.includes(term);
            item.style.display = match ? '' : 'none';
        });
    }
}

// Initialize on DOM ready
onReady(() => {
    const faq = new Faq();
    faq.init();
});

export default Faq;
