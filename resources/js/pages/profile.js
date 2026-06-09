/**
 * Profile Page JavaScript
 * Handles profile modals and mobile menu
 */

import { Modal } from '../components/Modal.js';
import { onReady } from '../utils/dom.js';
import { ProfileHistoryManager } from './ProfileHistoryManager.js';

class Profile {
    constructor() {
        this.modal = null;
    }

    init() {
        this.modal = new Modal();
        
        // Register profile modals
        const modalIds = ['nameModal', 'emailModal', 'phoneModal', 'passwordModal'];
        
        modalIds.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                const key = id.replace('Modal', '');
                this.modal.register(key, element);
            }
        });

        this.initMobileMenu();
        this.setupEventListeners();
        this.initHistoryManagers();
    }

    initHistoryManagers() {
        // Bookings Manager
        new ProfileHistoryManager({
            containerId: 'bookingsContainer',
            cardClass: 'booking-card',
            searchInputId: 'bookingSearch',
            filterBtnId: 'bookingFilterBtn',
            filterDropdownId: 'bookingFilterDropdown',
            filterOptClass: 'booking-filter-opt',
            loadMoreBtnId: 'loadMoreBooking',
            pageSize: 5
        });

        // Reschedule Manager
        new ProfileHistoryManager({
            containerId: 'reschedulesContainer',
            cardClass: 'reschedule-card',
            searchInputId: 'rescheduleSearch',
            filterBtnId: 'rescheduleFilterBtn',
            filterDropdownId: 'rescheduleFilterDropdown',
            filterOptClass: 'reschedule-filter-opt',
            loadMoreBtnId: 'loadMoreReschedule',
            pageSize: 5
        });

        // Cancellation Manager
        new ProfileHistoryManager({
            containerId: 'cancellationsContainer',
            cardClass: 'cancellation-card',
            searchInputId: 'cancellationSearch',
            filterBtnId: 'cancellationFilterBtn',
            filterDropdownId: 'cancellationFilterDropdown',
            filterOptClass: 'cancellation-filter-opt',
            loadMoreBtnId: 'loadMoreCancellation',
            pageSize: 5
        });
    }

    /**
     * Initialize mobile menu dropdown
     */
    initMobileMenu() {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navigationMenu = document.getElementById('navigationMenu');
        const dropdownArrow = document.getElementById('dropdownArrow');

        if (mobileMenuToggle && navigationMenu && dropdownArrow) {
            mobileMenuToggle.addEventListener('click', () => {
                const isOpen = !navigationMenu.classList.contains('hidden');

                if (isOpen) {
                    // Close animation
                    navigationMenu.classList.add('opacity-0', 'scale-95', '-translate-y-2');
                    navigationMenu.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                    dropdownArrow.classList.remove('rotate-180');

                    setTimeout(() => {
                        navigationMenu.classList.add('hidden');
                    }, 300);
                } else {
                    // Open animation
                    navigationMenu.classList.remove('hidden');

                    requestAnimationFrame(() => {
                        navigationMenu.classList.remove('opacity-0', 'scale-95', '-translate-y-2');
                        navigationMenu.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                    });

                    dropdownArrow.classList.add('rotate-180');
                }
            });
        }
    }

    /**
     * Setup all event listeners
     */
    setupEventListeners() {
        // Open modal buttons
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', (e) => {
                const modalType = e.currentTarget.dataset.openModal;
                this.modal.open(modalType);
            });
        });

        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', (e) => {
                const modalType = e.currentTarget.dataset.closeModal;
                this.modal.close(modalType);
            });
        });

        // Close on backdrop click
        Object.entries(this.modal.modals).forEach(([type, modal]) => {
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.modal.close(type);
                    }
                });
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.modal.closeAll();
            }
        });
    }
}

// Initialize on DOM ready
onReady(() => {
    const profile = new Profile();
    profile.init();
});

export default Profile;
