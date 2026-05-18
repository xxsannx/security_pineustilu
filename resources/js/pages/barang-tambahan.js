/**
 * Barang Tambahan Page JavaScript
 * Handles item interactions, search/filter, and price calculations
 */

import { onReady, qsa } from '../utils/dom.js';
import { formatCurrency, parsePrice } from '../utils/helpers.js';

class BarangTambahan {
    constructor() {
        this.itemRows = [];
        this.itemCards = [];
        this.categoryBtns = [];
    }

    init() {
        this.itemRows = qsa('.extras-row');
        this.itemCards = qsa('.extras-card');
        this.categoryBtns = qsa('[data-category]');

        this.setupHoverEffects();
        this.setupSearch();
        this.setupCategoryFilter();
        this.setupPriceCalculation();
    }

    setupHoverEffects() {
        this.itemRows.forEach(row => {
            row.style.transition = 'all 0.3s ease';
            row.style.cursor = 'pointer';

            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = 'rgba(1, 114, 73, 0.05)';
                row.style.transform = 'translateX(4px)';
                row.style.borderRadius = '8px';
            });

            row.addEventListener('mouseleave', () => {
                row.style.backgroundColor = '';
                row.style.transform = '';
            });
        });
    }

    setupSearch() {
        const searchInput = document.getElementById('searchItems');
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase().trim();

            this.itemRows.forEach(row => {
                const itemName = row.querySelector('.extras-name')?.textContent.toLowerCase() || '';
                const itemNote = row.querySelector('.extras-note')?.textContent.toLowerCase() || '';
                const matches = itemName.includes(searchTerm) || itemNote.includes(searchTerm);
                row.style.display = matches ? '' : 'none';
            });
        });
    }

    setupCategoryFilter() {
        this.categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.getAttribute('data-category');

                // Update active button state
                this.categoryBtns.forEach(b => {
                    b.classList.remove('active', 'bg-[#017249]', 'text-white');
                });
                btn.classList.add('active', 'bg-[#017249]', 'text-white');

                // Filter cards
                this.itemCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-card-category');
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = '';
                        card.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    setupPriceCalculation() {
        const itemCheckboxes = qsa('.item-checkbox');
        const quantityInputs = qsa('.item-quantity');
        const totalDisplay = document.getElementById('totalPrice');

        if (!itemCheckboxes.length || !totalDisplay) return;

        const calculateTotal = () => {
            let total = 0;

            itemCheckboxes.forEach((checkbox, index) => {
                if (checkbox.checked) {
                    const row = checkbox.closest('.extras-row');
                    const priceEl = row?.querySelector('.extras-price');
                    const qtyInput = quantityInputs[index];

                    if (priceEl) {
                        const price = parsePrice(priceEl.textContent);
                        const qty = parseInt(qtyInput?.value) || 1;
                        total += price * qty;
                    }
                }
            });

            totalDisplay.textContent = formatCurrency(total);
        };

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        quantityInputs.forEach(input => {
            input.addEventListener('change', calculateTotal);
        });
    }
}

// Initialize on DOM ready
onReady(() => {
    const barangTambahan = new BarangTambahan();
    barangTambahan.init();
});

export default BarangTambahan;
