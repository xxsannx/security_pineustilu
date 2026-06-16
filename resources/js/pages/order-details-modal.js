/**
 * Order Details Modal JavaScript
 * Handles opening and populating the order details modal
 */

class OrderDetailsModal {
    constructor() {
        this.modal = null;
        this.closeBtn = null;
        this.bookingData = null;
    }

    init() {
        this.modal = document.getElementById('orderDetailsModal');
        this.closeBtn = document.getElementById('closeOrderDetailsBtn');

        if (!this.modal || !this.closeBtn) {
            return;
        }

        // Setup close button
        this.closeBtn.addEventListener('click', () => this.close());

        // Close on backdrop click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.close();
            }
        });

        // Setup all "Order Details" buttons
        this.setupOrderDetailButtons();
    }

    /**
     * Setup click handlers for all order details buttons
     */
    setupOrderDetailButtons() {
        const buttons = document.querySelectorAll('[data-booking-details]');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const bookingData = JSON.parse(button.dataset.bookingDetails || '{}');
                this.open(bookingData);
            });
        });
    }

    /**
     * Open modal with booking data
     */
    open(bookingData) {
        this.bookingData = bookingData;

        // Show modal with animation
        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex');

        // Start loading
        this.showLoading();

        // Simulate small delay for smooth transition
        setTimeout(() => {
            this.populateModal(bookingData);
        }, 300);
    }

    /**
     * Close modal
     */
    close() {
        this.modal.classList.add('hidden');
        this.modal.classList.remove('flex');
    }

    /**
     * Show loading state
     */
    showLoading() {
        document.getElementById('orderLoading').classList.remove('hidden');
        document.getElementById('orderError').classList.add('hidden');
        document.getElementById('orderContent').classList.add('hidden');
    }

    /**
     * Show error state
     */
    showError(message = 'Failed to load order details') {
        document.getElementById('orderLoading').classList.add('hidden');
        document.getElementById('orderError').classList.remove('hidden');
        document.getElementById('orderContent').classList.add('hidden');
        document.getElementById('orderErrorText').textContent = message;
    }

    /**
     * Show content state
     */
    showContent() {
        document.getElementById('orderLoading').classList.add('hidden');
        document.getElementById('orderError').classList.add('hidden');
        document.getElementById('orderContent').classList.remove('hidden');
    }

    /**
     * Populate modal with booking data
     */
    populateModal(data) {
        try {
            // Booking ID
            const bookingType = data.area_type === 'Outbound' ? 'OUTBOUND' : 'GLAMPING';
            document.getElementById('bookingId').textContent =
                `#${bookingType}${String(data.id).padStart(8, '0')}`;

            // Update progress stepper
            this.updateProgressStepper(data.status);

            // Date section
            this.populateDateSection(data);

            // Guest details
            document.getElementById('guestName').textContent = data.guest_name || '-';
            document.getElementById('guestPhone').textContent = data.guest_phone || '-';
            document.getElementById('guestEmail').textContent = data.guest_email || '-';
            document.getElementById('guestCount').textContent = `${data.guests || 0} People`;

            // Reservation details
            this.populateReservationDetails(data);

            // Add-ons
            if (data.amenities && data.amenities.length > 0) {
                this.populateAddons(data.amenities);
            } else {
                document.getElementById('addonsSection').classList.add('hidden');
            }

            // Payment summary
            this.populatePaymentSummary(data);

            // Update action buttons with booking code
            this.updateActionButtons(data);

            // Show content
            this.showContent();
        } catch (error) {
            console.error('Error populating modal:', error);
            this.showError('Failed to display order details');
        }
    }

    /**
     * Update reschedule and cancellation button URLs with booking code
     */
    updateActionButtons(data) {
        if (data.booking_code) {
            const rescheduleLink = this.modal.querySelector('a[href*="reschedule"]');
            const cancellationLink = this.modal.querySelector('a[href*="cancellation"]');

            const params = new URLSearchParams();
            params.append('code', data.booking_code);
            if (data.guest_email) {
                params.append('email', data.guest_email);
            }
            const queryString = params.toString();

            if (rescheduleLink) {
                rescheduleLink.href = `/reschedule?${queryString}`;
            }

            if (cancellationLink) {
                cancellationLink.href = `/cancellation?${queryString}`;
            }
        }
    }

    /**
     * Update progress stepper based on status
     */
    updateProgressStepper(status) {
        const statusValue = typeof status === 'object' ? status.value : status;
        const statuses = ['proses', 'pembayaran', 'berhasil', 'berjalan', 'selesai'];
        const currentIndex = statuses.indexOf(statusValue);

        // Reset all
        ['Booking', 'Payment', 'Confirmed', 'Progress', 'Completed'].forEach((step, index) => {
            const element = document.getElementById(`status${step}`);
            const span = element.nextElementSibling;

            if (index <= currentIndex) {
                // Active or completed
                element.classList.remove('bg-gray-300', 'text-gray-600');
                element.classList.add('bg-[#017249]', 'text-white');
                span.classList.remove('text-gray-600');
                span.classList.add('text-[#017249]');

                if (index === currentIndex) {
                    // Current step - add ring
                    element.classList.add('ring-2', 'sm:ring-4', 'ring-[#017249]/20');
                } else {
                    // Completed step - show checkmark
                    element.classList.remove('ring-2', 'sm:ring-4', 'ring-[#017249]/20');
                    element.innerHTML = `
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    `;
                }
            }
        });

        // Update progress line
        document.getElementById('progressLine1').style.width = (currentIndex >= 1) ? '100%' : '0%';
    }

    /**
     * Populate date section
     */
    populateDateSection(data) {
        const dateSection = document.getElementById('dateSection');

        if (data.area_type === 'Outbound') {
            // Activity date for outbound
            dateSection.innerHTML = `
                <h4 class="text-xs font-semibold text-[#017249] mb-2">Tanggal Kegiatan</h4>
                <p class="text-sm font-bold text-gray-800">${this.formatDate(data.check_in)}</p>
            `;
        } else {
            // Check-in and Check-out for glamping
            dateSection.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xs font-semibold text-[#017249] mb-2">Check-In</h4>
                        <p class="text-sm font-bold text-gray-800">${this.formatDate(data.check_in)}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-[#017249] mb-2">Check-Out</h4>
                        <p class="text-sm font-bold text-gray-800">${this.formatDate(data.check_out)}</p>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Populate reservation details based on type
     */
    populateReservationDetails(data) {
        const container = document.getElementById('reservationDetails');

        if (data.area_type === 'Outbound') {
            // Outbound details
            container.innerHTML = `
                <div class="space-y-2">
                    <div>
                        <span class="font-semibold text-gray-600 block mb-1">Paket Outbound</span>
                        <span class="text-gray-900 font-medium">${data.outbound || '-'}</span>
                    </div>
                    ${data.variant ? `
                        <div>
                            <span class="font-semibold text-gray-600 block mb-1">Varian</span>
                            <span class="text-gray-900 font-medium">${data.variant}</span>
                        </div>
                    ` : ''}
                </div>
            `;
        } else {
            // Glamping details
            container.innerHTML = `
                <div class="space-y-2">
                    <div>
                        <span class="font-semibold text-gray-600 block mb-1">Area</span>
                        <span class="text-gray-900 font-medium">${data.area || '-'}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-600 block mb-1">Deck</span>
                        <span class="text-gray-900 font-medium">${data.deck || '-'}</span>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Populate add-ons list
     */
    populateAddons(amenities) {
        const section = document.getElementById('addonsSection');
        const list = document.getElementById('addonsList');

        section.classList.remove('hidden');

        list.innerHTML = amenities.map(amenity => `
            <li class="flex items-start gap-2">
                <svg class="w-4 h-4 text-[#017249] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700">${amenity}</span>
            </li>
        `).join('');
    }

    /**
     * Populate payment summary
     */
    populatePaymentSummary(data) {
        const total = data.total || 0;
        const fees = data.extra_charge || 0;
        const subtotal = total - fees;

        document.getElementById('paymentSubtotal').textContent = this.formatCurrency(subtotal);
        document.getElementById('paymentFees').textContent = this.formatCurrency(fees);
        document.getElementById('paymentTotal').textContent = this.formatCurrency(total);
    }

    /**
     * Format date to readable format
     */
    formatDate(dateString) {
        if (!dateString) return '-';

        try {
            const date = new Date(dateString);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        } catch (e) {
            return dateString;
        }
    }

    /**
     * Format currency to Indonesian Rupiah
     */
    formatCurrency(amount) {
        return 'Rp. ' + Number(amount).toLocaleString('id-ID');
    }
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const orderModal = new OrderDetailsModal();
        orderModal.init();
    });
} else {
    const orderModal = new OrderDetailsModal();
    orderModal.init();
}

export default OrderDetailsModal;
