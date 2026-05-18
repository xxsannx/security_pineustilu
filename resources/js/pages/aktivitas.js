/**
 * Aktivitas Page JavaScript
 * Handles animations and lazy loading (no modal/lightbox needed)
 */

import { onReady, qsa } from '../utils/dom.js';

class Aktivitas {
    init() {
        this.setupRaftingSlider();
        this.animateInView();
        this.enableLazyImages();
        this.setupGridToggle();
    }

    /**
     * Setup rafting image slider interaction.
     */
    setupRaftingSlider() {
        const slider = document.querySelector('[data-aktivitas-slider="arung-jeram"]');
        if (!slider) return;

        const images = qsa('[data-slider-image]', slider);
        const dots = qsa('[data-slider-dot]', slider);

        if (images.length < 2 || dots.length < 2) return;

        let currentSlide = 0;

        const render = () => {
            images.forEach((image, index) => {
                image.classList.toggle('opacity-100', index === currentSlide);
                image.classList.toggle('opacity-0', index !== currentSlide);
            });

            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-white', index === currentSlide);
                dot.classList.toggle('w-4', index === currentSlide);
                dot.classList.toggle('bg-white/50', index !== currentSlide);
                dot.classList.toggle('w-2', index !== currentSlide);
            });
        };

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                render();
            });
        });

        setInterval(() => {
            currentSlide = currentSlide === 0 ? 1 : 0;
            render();
        }, 3000);

        render();
    }

    /**
     * Animate elements when they come into view
     */
    animateInView() {
        const main = document.querySelector('main');
        if (!main) return;

        const targets = qsa('section, article', main);
        if (!targets.length) return;

        targets.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(12px)';
            el.style.transition = 'opacity 500ms ease, transform 500ms ease';
            el.style.willChange = 'opacity, transform';
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'none';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        targets.forEach(el => observer.observe(el));
    }

    /**
     * Enable lazy loading for all images
     */
    enableLazyImages() {
        const main = document.querySelector('main');
        if (!main) return;

        qsa('img', main).forEach(img => {
            if (!img.hasAttribute('loading')) img.setAttribute('loading', 'lazy');
            if (!img.hasAttribute('decoding')) img.setAttribute('decoding', 'async');
        });
    }

    /**
     * Setup grid toggle for "Activities Around Pineus Tilu" section
     */
    setupGridToggle() {
        const headings = qsa('h2');
        const targetHeading = headings.find(h => {
            const text = (h.textContent || '').toLowerCase();
            return text.includes('activities around');
        });

        if (!targetHeading) return;

        // Find the grid container after the heading
        let gridContainer = targetHeading.nextElementSibling;
        while (gridContainer && !gridContainer.classList.contains('grid')) {
            gridContainer = gridContainer.nextElementSibling;
        }

        if (!gridContainer) return;

        // Get all grid items
        const items = qsa('.grid > article', gridContainer.parentElement) || 
                      qsa('.grid > div', gridContainer.parentElement);
        
        if (items.length <= 6) return; // No need for toggle if 6 or less items

        // Create toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'mt-6 px-6 py-2 bg-[#017249] text-white rounded-lg hover:bg-[#015a3a] transition-all duration-300 cursor-pointer';
        toggleBtn.textContent = 'Show All';
        
        let isExpanded = false;

        // Initially hide items after 6
        items.forEach((item, index) => {
            if (index >= 6) item.style.display = 'none';
        });

        toggleBtn.addEventListener('click', () => {
            isExpanded = !isExpanded;
            items.forEach((item, index) => {
                if (index >= 6) {
                    item.style.display = isExpanded ? '' : 'none';
                }
            });
            toggleBtn.textContent = isExpanded ? 'Show Less' : 'Show All';
        });

        // Insert button after grid
        gridContainer.parentElement.appendChild(toggleBtn);
    }
}

// Initialize on DOM ready
onReady(() => {
    const aktivitas = new Aktivitas();
    aktivitas.init();
});

export default Aktivitas;
