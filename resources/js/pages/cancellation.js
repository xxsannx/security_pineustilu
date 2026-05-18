/**
 * Cancellation Page JavaScript
 * Handles form validation and submission
 */

import { onReady } from '../utils/dom.js';
import { sanitize, isValidRedeemCode } from '../utils/helpers.js';

class Cancellation {
    constructor() {
        this.form = null;
        this.input = null;
        this.button = null;
        this.msg = null;
    }

    init() {
        this.form = document.querySelector('form[action*="cancellation"]');
        this.input = document.getElementById('cancel-redeem');

        if (!this.form || !this.input) return;

        this.button = this.form.querySelector('button[type="submit"]');
        this.createMessageElement();
        this.bindEvents();
    }

    createMessageElement() {
        this.msg = document.createElement('p');
        this.msg.className = 'mt-3 text-sm';
        this.form.after(this.msg);
    }

    bindEvents() {
        this.input.addEventListener('input', () => {
            this.input.value = sanitize(this.input.value);
            this.clearError();
        });

        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    clearError() {
        this.input.classList.remove('border-red-500', 'ring-red-500');
        this.msg.textContent = '';
        this.msg.classList.remove('text-red-600', 'text-green-600');
    }

    showError(message) {
        this.msg.textContent = message;
        this.msg.classList.add('text-red-600');
        this.input.classList.add('border-red-500', 'ring-red-500');
    }

    setLoading(loading) {
        if (loading) {
            this.button.dataset.originalText = this.button.textContent;
            this.button.textContent = 'Processing...';
            this.button.disabled = true;
            this.button.classList.add('opacity-60', 'cursor-not-allowed');
        } else {
            this.button.textContent = this.button.dataset.originalText || 'Submit';
            this.button.disabled = false;
            this.button.classList.remove('opacity-60', 'cursor-not-allowed');
        }
    }

    handleSubmit(e) {
        e.preventDefault();
        const code = sanitize(this.input.value);

        if (!isValidRedeemCode(code)) {
            this.showError('Invalid redeem code. Use 6–32 alphanumeric characters and hyphens (-).');
            return;
        }

        this.setLoading(true);

        // Redirect with query parameter
        const url = new URL(this.form.action, window.location.origin);
        url.searchParams.set('code', code);
        window.location.href = url.toString();

        // Safety re-enable if redirect is blocked
        setTimeout(() => this.setLoading(false), 5000);
    }
}

// Initialize on DOM ready
onReady(() => {
    const cancellation = new Cancellation();
    cancellation.init();
});

export default Cancellation;
