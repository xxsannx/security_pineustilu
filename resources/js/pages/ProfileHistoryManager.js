export class ProfileHistoryManager {
    constructor(options) {
        this.containerId = options.containerId;
        this.cardClass = options.cardClass;
        this.searchInputId = options.searchInputId;
        this.filterBtnId = options.filterBtnId;
        this.filterDropdownId = options.filterDropdownId;
        this.filterOptClass = options.filterOptClass;
        this.loadMoreBtnId = options.loadMoreBtnId;
        this.pageSize = options.pageSize || 5;

        this.cards = [];
        this.visibleCount = this.pageSize;
        this.currentSearch = '';
        this.currentFilter = 'all';

        this.init();
    }

    init() {
        const container = document.getElementById(this.containerId);
        if (!container) return; // Tab not active

        this.cards = Array.from(container.querySelectorAll(`.${this.cardClass}`));
        if (this.cards.length === 0) return;

        this.setupSearch();
        this.setupFilter();
        this.setupLoadMore();

        this.applyFilters();
    }

    setupSearch() {
        const searchInput = document.getElementById(this.searchInputId);
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            this.currentSearch = e.target.value.toLowerCase().trim();
            this.visibleCount = this.pageSize; // Reset pagination on search
            this.applyFilters();
        });
    }

    setupFilter() {
        const filterBtn = document.getElementById(this.filterBtnId);
        const filterDropdown = document.getElementById(this.filterDropdownId);
        if (!filterBtn || !filterDropdown) return;

        // Toggle dropdown
        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
        });

        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            if (!filterBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
                filterDropdown.classList.add('hidden');
            }
        });

        // Handle filter selection
        const filterOpts = filterDropdown.querySelectorAll(`.${this.filterOptClass}`);
        filterOpts.forEach(opt => {
            opt.addEventListener('click', (e) => {
                this.currentFilter = e.currentTarget.dataset.value;
                this.visibleCount = this.pageSize; // Reset pagination on filter
                this.applyFilters();
                filterDropdown.classList.add('hidden');
            });
        });
    }

    setupLoadMore() {
        const loadMoreBtn = document.getElementById(this.loadMoreBtnId);
        if (!loadMoreBtn) return;

        loadMoreBtn.addEventListener('click', () => {
            this.visibleCount += this.pageSize;
            this.applyFilters();
        });
    }

    applyFilters() {
        let matchingCards = 0;

        this.cards.forEach((card) => {
            // Check Search
            let matchesSearch = false;
            if (this.currentSearch === '') {
                matchesSearch = true;
            } else {
                const searchId = card.dataset.id ? card.dataset.id.toLowerCase() : '';
                if (this.containerId === 'bookingsContainer') {
                    matchesSearch = searchId.includes(this.currentSearch);
                } else {
                    const textContent = card.innerText.toLowerCase();
                    matchesSearch = searchId.includes(this.currentSearch) || textContent.includes(this.currentSearch);
                }
            }

            // Check Filter
            const cardStatus = card.dataset.statusVal || card.dataset.status || '';
            const matchesFilter = this.currentFilter === 'all' || cardStatus === this.currentFilter;

            if (matchesSearch && matchesFilter) {
                // Check Pagination Limit
                if (matchingCards < this.visibleCount) {
                    if (card.style.display === 'none') {
                        card.style.display = ''; // Show
                    }
                } else {
                    if (card.style.display !== 'none') {
                        card.style.display = 'none'; // Hidden due to pagination
                    }
                }
                matchingCards++;
            } else {
                if (card.style.display !== 'none') {
                    card.style.display = 'none'; // Hidden due to filter/search
                }
            }
        });

        // Update Load More Button visibility
        const loadMoreBtn = document.getElementById(this.loadMoreBtnId);
        if (loadMoreBtn) {
            if (matchingCards > this.visibleCount) {
                if (loadMoreBtn.style.display === 'none') loadMoreBtn.style.display = ''; // Show
            } else {
                if (loadMoreBtn.style.display !== 'none') loadMoreBtn.style.display = 'none'; // Hide
            }
        }

        // Refresh AOS if applicable
        setTimeout(() => {
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }, 50);
    }
}
