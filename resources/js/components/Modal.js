/**
 * Modal Component
 * Reusable modal handler with animations
 */

export class Modal {
    constructor(options = {}) {
        this.modals = {};
        this.animationClass = options.animationClass || 'animate-fadeInUp';
        this.init();
    }

    /**
     * Initialize modals from data attributes
     */
    init() {
        // Get all elements with data-modal-id attribute
        document.querySelectorAll('[data-modal-id]').forEach(modal => {
            const id = modal.dataset.modalId;
            this.modals[id] = modal;
        });

        this.setupEventListeners();
    }

    /**
     * Register a modal by ID
     * @param {string} id 
     * @param {Element} element 
     */
    register(id, element) {
        if (element) {
            this.modals[id] = element;
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Open modal buttons
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', (e) => {
                const modalType = e.currentTarget.dataset.openModal;
                this.open(modalType);
            });
        });

        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', (e) => {
                const modalType = e.currentTarget.dataset.closeModal;
                this.close(modalType);
            });
        });

        // Close on backdrop click
        Object.entries(this.modals).forEach(([type, modal]) => {
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.close(type);
                    }
                });
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAll();
            }
        });
    }

    /**
     * Open a modal
     * @param {string} type 
     */
    open(type) {
        const modal = this.modals[type];
        if (!modal) return;

        modal.style.display = 'flex';
        modal.classList.remove('hidden');

        const modalContent = modal.querySelector('div > div');
        if (modalContent) {
            modalContent.classList.add(this.animationClass);
        }

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';
        document.documentElement.style.overflow = 'hidden';
    }

    /**
     * Close a modal
     * @param {string} type 
     */
    close(type) {
        const modal = this.modals[type];
        if (!modal) return;

        const modalContent = modal.querySelector('div > div');
        if (modalContent) {
            modalContent.classList.remove(this.animationClass);
        }

        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }, 200);

        // Restore body scroll
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        document.documentElement.style.overflow = '';
    }

    /**
     * Close all open modals
     */
    closeAll() {
        Object.keys(this.modals).forEach(key => {
            const modal = this.modals[key];
            if (modal && !modal.classList.contains('hidden')) {
                this.close(key);
            }
        });
    }
}

export default Modal;
