/**
 * Cerita Page JavaScript
 * Handles language switching for multilingual content
 */

import { onReady, qsa } from '../utils/dom.js';

class Cerita {
    constructor() {
        this.config = {
            defaultLang: 'en',
            languages: {
                en: { title: 'STORY', name: 'English' },
                ja: { title: 'ストーリー', name: '日本語' }
            }
        };
        this.DOM = {};
    }

    init() {
        this.cacheDOMElements();
        this.bindEvents();
        // Always start with English
        this.switchLanguage(this.config.defaultLang);
    }

    cacheDOMElements() {
        this.DOM = {
            buttons: qsa('.lang-btn'),
            boxes: qsa('.story-box'),
            header: document.getElementById('storyHeader')
        };
    }

    bindEvents() {
        this.DOM.buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const lang = btn.dataset.lang;
                if (this.isValidLanguage(lang)) {
                    this.switchLanguage(lang);
                }
            });
        });
    }

    isValidLanguage(lang) {
        return Object.prototype.hasOwnProperty.call(this.config.languages, lang);
    }

    switchLanguage(lang) {
        this.updateStoryBoxes(lang);
        this.updateButtons(lang);
        this.updateHeader(lang);
    }

    updateStoryBoxes(selectedLang) {
        this.DOM.boxes.forEach(box => {
            const boxInner = box.querySelector('[data-aos]');
            const isSelected = box.dataset.lang === selectedLang;

            if (isSelected) {
                box.classList.remove('hidden');

                // Reset AOS animation
                if (boxInner && typeof AOS !== 'undefined') {
                    boxInner.classList.remove('aos-init', 'aos-animate');
                    void boxInner.offsetWidth; // Force reflow

                    requestAnimationFrame(() => {
                        AOS.refresh();
                        setTimeout(() => {
                            boxInner.classList.add('aos-init');
                        }, 50);
                    });
                }
            } else {
                box.classList.add('hidden');
            }
        });
    }

    updateButtons(selectedLang) {
        this.DOM.buttons.forEach(btn => {
            const isActive = btn.dataset.lang === selectedLang;
            btn.setAttribute('aria-pressed', isActive);

            if (isActive) {
                btn.classList.add('!bg-brand-primary', '!text-white', '!border-brand-primary', 'shadow-lg', 'scale-105');
                btn.classList.remove('hover:-translate-y-1');
            } else {
                btn.classList.remove('!bg-brand-primary', '!text-white', '!border-brand-primary', 'shadow-lg', 'scale-105');
                btn.classList.add('hover:-translate-y-1');
            }
        });
    }

    updateHeader(selectedLang) {
        if (this.DOM.header && this.config.languages[selectedLang]) {
            this.DOM.header.textContent = this.config.languages[selectedLang].title;
        }
    }
}

// Initialize on DOM ready
onReady(() => {
    const cerita = new Cerita();
    cerita.init();
});

export default Cerita;
