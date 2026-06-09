/**
 * Cancel Booking Modal Handler
 * Handles the cancel booking confirmation modal and AJAX request to delete booking
 */

document.addEventListener('DOMContentLoaded', function() {
    const backButton = document.getElementById('backButton');
    const cancelModal = document.getElementById('cancelModal');
    const cancelModalClose = document.getElementById('cancelModalClose');
    const confirmCancelBooking = document.getElementById('confirmCancelBooking');

    if (backButton && cancelModal) {
        // Open modal when back button is clicked
        backButton.addEventListener('click', function() {
            cancelModal.classList.remove('hidden');
            cancelModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });

        // Close modal
        const closeModal = function() {
            cancelModal.classList.add('hidden');
            cancelModal.classList.remove('flex');
            document.body.style.overflow = '';
        };

        // Close on button click
        if (cancelModalClose) {
            cancelModalClose.addEventListener('click', closeModal);
        }

        // Confirm cancel booking - send request to delete booking
        if (confirmCancelBooking) {
            confirmCancelBooking.addEventListener('click', function() {
                // Disable button to prevent double click
                this.disabled = true;
                this.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                // Get update URL from data attribute
                const updateStatusUrl = document.body.dataset.updateStatusUrl;

                // Send request to cancel booking
                fetch(updateStatusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: 'dibatalkan'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Redirect based on booking type
                    const bookingType = document.body.dataset.bookingType || 'glamping';
                    const redirectUrl = bookingType === 'outbound' ? '/reservasi/outbound' : '/reservasi/glamping';
                    window.location.href = data.redirect || redirectUrl;
                })
                .catch(error => {
                    console.error('Error canceling booking:', error);
                    // Redirect based on booking type even on error
                    const bookingType = document.body.dataset.bookingType || 'glamping';
                    const redirectUrl = bookingType === 'outbound' ? '/reservasi/outbound' : '/reservasi/glamping';
                    window.location.href = redirectUrl;
                });
            });
        }

        // Confirm return to main menu
        const confirmReturnMainMenu = document.getElementById('confirmReturnMainMenu');
        if (confirmReturnMainMenu) {
            confirmReturnMainMenu.addEventListener('click', function() {
                // Redirect to home page
                window.location.href = '/';
            });
        }

        // Close on backdrop click
        cancelModal.addEventListener('click', function(e) {
            if (e.target === cancelModal) {
                closeModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !cancelModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    }
});
