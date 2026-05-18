/**
 * Pedoman Page JavaScript
 * Handles accordion, smooth scrolling, and print functionality
 */

import { Accordion } from '../components/Accordion.js';
import { onReady, qsa } from '../utils/dom.js';

class Pedoman {
    constructor() {
        this.accordion = null;
    }

    init() {
        this.accordion = new Accordion({
            toggleSelector: '[data-accordion-header]'
        }).init();

        this.setupSmoothScroll();
        this.setupTocHighlight();
        this.setupPrint();
        this.setupCopyLinks();
        this.handleUrlHash();
    }

    handleUrlHash() {
        const hash = window.location.hash;
        if (hash) {
            const target = document.querySelector(hash);
            if (target) {
                setTimeout(() => {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }
        }
    }

    setupSmoothScroll() {
        qsa('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update URL hash without jumping
                    history.pushState(null, null, targetId);
                }
            });
        });
    }

    setupTocHighlight() {
        const tocLinks = qsa('.toc-link');
        const sections = qsa('section[id]');

        if (!tocLinks.length || !sections.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    tocLinks.forEach(link => {
                        link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
                    });
                }
            });
        }, { rootMargin: '-20% 0px -80% 0px' });

        sections.forEach(section => observer.observe(section));
    }

    setupPrint() {
        const printBtn = document.getElementById('printPedoman');
        if (printBtn) {
            printBtn.addEventListener('click', () => window.print());
        }
    }

    setupCopyLinks() {
        qsa('[data-copy-link]').forEach(btn => {
            btn.addEventListener('click', () => {
                const sectionId = btn.dataset.copyLink;
                const url = `${window.location.origin}${window.location.pathname}#${sectionId}`;
                
                navigator.clipboard.writeText(url).then(() => {
                    // Show feedback
                    const originalText = btn.textContent;
                    btn.textContent = 'Copied!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                    }, 2000);
                });
            });
        });
    }
}

// Initialize on DOM ready
onReady(() => {
    const pedoman = new Pedoman();
    pedoman.init();
});

export default Pedoman;
