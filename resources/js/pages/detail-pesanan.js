/**
 * Detail Pesanan (Order Details) Page JavaScript
 * Handles booking status updates and payment flow
 * Integrated with Midtrans Snap payment gateway
 */

import { onReady, qs } from '../utils/dom.js';

class DetailPesanan {
    updateUrl = null;
    csrfToken = null;
    bookingToken = null;
    snapLoaded = false;

    /**
     * Initialize the page
     */
    init() {
        this.updateUrl = document.body.dataset.updateStatusUrl;
        this.csrfToken = qs('meta[name="csrf-token"]')?.content;
        this.bookingToken = document.body.dataset.bookingToken;

        if (!this.updateUrl || !this.csrfToken) {
            return;
        }

        this.bindEvents();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        const proceedBtn = qs('[data-action="proceed-to-payment"]');
        if (proceedBtn) {
            proceedBtn.addEventListener('click', () => this.proceedToPayment());
        }

        const payBtn = qs('[data-action="complete-payment"]');
        if (payBtn) {
            payBtn.addEventListener('click', () => this.completePayment());
        }

        // Copy booking code functionality
        const copyBtn = qs('#copyCodeBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', () => this.copyBookingCode());
        }
    }

    /**
     * Copy booking code to clipboard
     */
    copyBookingCode() {
        const codeEl = qs('#bookingCode');
        if (!codeEl) return;

        const code = codeEl.textContent.trim();

        navigator.clipboard.writeText(code).then(() => {
            // Show success feedback
            const copyBtn = qs('#copyCodeBtn');
            const originalHtml = copyBtn.innerHTML;
            copyBtn.innerHTML = `
                <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            `;

            setTimeout(() => {
                copyBtn.innerHTML = originalHtml;
            }, 2000);
        }).catch(err => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = code;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Kode berhasil disalin: ' + code);
        });
    }

    /**
     * Submit a form to update booking status
     * @param {string} newStatus - The new status to set
     */
    updateBookingStatus(newStatus) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.updateUrl;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = this.csrfToken;
        form.appendChild(csrfInput);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);

        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Proceed to payment step (update status to pembayaran)
     */
    proceedToPayment() {
        this.updateBookingStatus('pembayaran');
    }

    /**
     * Complete payment - Initialize Midtrans Snap payment
     */
    completePayment() {
        const modal = qs('#snapPaymentModal');
        if (!modal) return;

        // Show modal
        modal.classList.remove('hidden');

        // Fetch snap token from backend
        this.fetchSnapToken();
    }

    /**
     * Fetch Snap token from backend API
     */
    async fetchSnapToken() {
        try {
            if (!this.bookingToken) {
                this.showSnapError('Booking token not found');
                return;
            }

            const response = await fetch(`/api/payment/snap-token/${this.bookingToken}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                this.showSnapError(data.message || 'Failed to generate payment token');
                return;
            }

            // Initialize Snap with token
            this.initializeSnap(data.token, data.client_key, data.snap_js_url);

        } catch (error) {
            console.error('Error fetching snap token:', error);
            this.showSnapError('Failed to load payment gateway. Please try again.');
        }
    }

    /**
     * Initialize and display Midtrans Snap payment
     */
    initializeSnap(snapToken, clientKey, snapJsUrl) {
        // Load Snap JS library if not already loaded
        if (!window.snap) {
            const script = document.createElement('script');
            script.src = snapJsUrl;
            script.setAttribute('data-client-key', clientKey);
            script.onload = () => {
                this.displaySnapPayment(snapToken);
            };
            script.onerror = () => {
                this.showSnapError('Failed to load payment gateway. Please check your connection.');
            };
            document.head.appendChild(script);
        } else {
            this.displaySnapPayment(snapToken);
        }
    }

    /**
     * Display Snap payment embedded in modal
     */
    displaySnapPayment(snapToken) {
        const loadingEl = qs('#snapLoading');
        const containerEl = qs('#snap-container');
        const errorEl = qs('#snapError');

        if (!containerEl) return;

        // Hide loading and error states
        if (loadingEl) loadingEl.style.display = 'none';
        if (errorEl) errorEl.classList.add('hidden');

        // Show container
        containerEl.style.display = 'block';

        // Use embedded mode (better for modal)
        window.snap.embed(snapToken, {
            embedId: 'snap-container',
            onSuccess: (result) => this.onPaymentSuccess(result),
            onPending: (result) => this.onPaymentPending(result),
            onError: (result) => this.onPaymentError(result),
            onClose: () => this.onPaymentClose(),
        });

        this.snapLoaded = true;
    }

    /**
     * Handle successful payment
     */
    onPaymentSuccess(result) {
        console.log('Payment successful:', result);

        // Close modal
        this.closeSnapModal();

        // Show success message
        alert('Payment successful! Your booking has been confirmed.');

        // Update booking status to berhasil
        this.updateBookingStatus('berhasil');
    }

    /**
     * Handle pending payment
     */
    onPaymentPending(result) {
        console.log('Payment pending:', result);
        alert('Payment is being processed. Please wait...');
    }

    /**
     * Handle payment error
     */
    onPaymentError(result) {
        console.error('Payment error:', result);
        this.showSnapError('Payment failed. ' + (result?.status_message || 'Please try again.'));
    }

    /**
     * Handle payment close
     */
    onPaymentClose() {
        console.log('Payment modal closed by user');
        // User closed the payment modal without completing
        // Don't update booking status
    }

    /**
     * Close Snap payment modal
     */
    closeSnapModal() {
        const modal = qs('#snapPaymentModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Show error in Snap modal
     */
    showSnapError(message) {
        const loadingEl = qs('#snapLoading');
        const containerEl = qs('#snap-container');
        const errorEl = qs('#snapError');
        const errorMessageEl = qs('#snapErrorMessage');

        if (loadingEl) loadingEl.style.display = 'none';
        if (containerEl) containerEl.style.display = 'none';

        if (errorEl && errorMessageEl) {
            errorMessageEl.textContent = message;
            errorEl.classList.remove('hidden');
        }
    }
}

// Initialize on DOM ready
onReady(() => {
    const detailPesanan = new DetailPesanan();
    detailPesanan.init();

    // Expose functions globally for backward compatibility
    globalThis.proceedToPayment = () => detailPesanan.proceedToPayment();
    globalThis.completePayment = () => detailPesanan.completePayment();
    globalThis.closeSnapModal = () => detailPesanan.closeSnapModal();
});

