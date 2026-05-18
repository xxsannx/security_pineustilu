/**
 * Availability Table Handler
 * Manages the interactive availability table with area switching and date range display
 */

document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('tableBody');
    const tableContent = document.getElementById('tableContent');
    const loadingState = document.getElementById('loadingState');
    const lastUpdate = document.getElementById('lastUpdate');
    const monthSelector = document.getElementById('monthSelector');
    const yearSelector = document.getElementById('yearSelector');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const refreshBtn = document.getElementById('refreshButton');
    
    let currentArea = window.defaultArea || 'pineus-tilu-1';
    let availabilityData = window.availabilityData || {};
    let areaUnitsData = window.areaUnitsData || {};
    
    // Current selected month and year
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    

    /**
     * Initialize the table with default area
     */
    function init() {
        // Populate year selector (current year - 1 to current year + 2)
        populateYearSelector();
        
        // Set current month and year
        monthSelector.value = currentMonth;
        yearSelector.value = currentYear;
        
        // Set up month/year change listeners
        monthSelector.addEventListener('change', handleMonthYearChange);
        yearSelector.addEventListener('change', handleMonthYearChange);
        
        // Set up navigation button listeners
        prevMonthBtn.addEventListener('click', handlePrevMonth);
        nextMonthBtn.addEventListener('click', handleNextMonth);
        
        // Set up refresh button listener
        if (refreshBtn) {
            refreshBtn.addEventListener('click', handleRefresh);
        }
        
        // Set up area button listeners
        const areaButtons = document.querySelectorAll('#areaItems button[data-area]');
        areaButtons.forEach((btn, index) => {
            btn.addEventListener('click', function() {
                const areaSlug = this.getAttribute('data-area');
                switchArea(areaSlug, index, this);
            });
        });

        // Initialize with default area (first button)
        if (areaButtons.length > 0) {
            const firstButton = areaButtons[0];
            const areaSlug = firstButton.getAttribute('data-area');
            updateKnobPosition(0);
            renderTable(areaSlug);
        }
    }

    /**
     * Populate year selector with range of years
     */
    function populateYearSelector() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear;
        const endYear = currentYear + 3; // Current year + 3 years ahead
        
        yearSelector.innerHTML = '';
        for (let year = startYear; year <= endYear; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelector.appendChild(option);
        }
    }

    /**
     * Handle month/year selector change
     */
    function handleMonthYearChange() {
        currentMonth = parseInt(monthSelector.value);
        currentYear = parseInt(yearSelector.value);
        fetchFreshData();
    }

    /**
     * Handle previous month button
     */
    function handlePrevMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        monthSelector.value = currentMonth;
        yearSelector.value = currentYear;
        fetchFreshData();
    }

    /**
     * Handle next month button
     */
    function handleNextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        monthSelector.value = currentMonth;
        yearSelector.value = currentYear;
        fetchFreshData();
    }

    /**
     * Handle refresh button - fetch fresh data
     */
    function handleRefresh() {
        fetchFreshData();
    }

    /**
     * Fetch fresh availability data from server (real-time update)
     */
    async function fetchFreshData() {
        try {
            // Show loading state
            showLoading();
            
            // Rotate refresh icon if exists
            if (refreshBtn) {
                const icon = refreshBtn.querySelector('svg');
                if (icon) {
                    icon.classList.add('animate-spin');
                }
            }
            
            // Build URL with query parameters
            const url = new URL('/availability/data', window.location.origin);
            url.searchParams.append('year', currentYear);
            url.searchParams.append('month', currentMonth);
            
            // Fetch fresh data
            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch availability data');
            }
            
            const result = await response.json();
            
            if (result.success && result.data) {
                // Update availability data
                availabilityData = result.data;
                
                // Re-render table with fresh data
                renderTable(currentArea);
                
                // Update last update timestamp
                updateLastUpdateTime();
                
                console.log('Availability data refreshed successfully');
            } else {
                throw new Error('Invalid response format');
            }
        } catch (error) {
            console.error('Error fetching availability data:', error);
            // Still render with cached data
            renderTable(currentArea);
        } finally {
            // Remove loading state
            hideLoading();
            
            // Stop refresh icon animation
            if (refreshBtn) {
                const icon = refreshBtn.querySelector('svg');
                if (icon) {
                    icon.classList.remove('animate-spin');
                }
            }
        }
    }

    /**
     * Update knob position on the track
     * @param {number} index - Index of the clicked button
     */
    function updateKnobPosition(index) {
        const areaItems = document.getElementById('areaItems');
        const areaTrack = document.getElementById('areaTrack');
        const knob = document.getElementById('areaKnob');
        
        if (!areaItems || !areaTrack || !knob) return;

        const buttons = areaItems.querySelectorAll('button[data-area]');
        if (index >= buttons.length) return;

        const button = buttons[index];
        const trackWidth = areaTrack.offsetWidth;
        const buttonRect = button.getBoundingClientRect();
        const trackRect = areaTrack.getBoundingClientRect();
        
        // Calculate position: center of button relative to track
        const buttonCenter = buttonRect.left + (buttonRect.width / 2) - trackRect.left;
        const knobWidth = knob.offsetWidth;
        const position = buttonCenter - (knobWidth / 2);
        
        // Ensure knob stays within track bounds
        const maxPosition = trackWidth - knobWidth;
        const finalPosition = Math.max(0, Math.min(position, maxPosition));
        
        knob.style.left = `${finalPosition}px`;
    }

    /**
     * Switch to different area
     * @param {string} areaSlug - The area slug to switch to
     * @param {number} index - Index of the button
     * @param {HTMLElement} buttonElement - The clicked button element
     */
    function switchArea(areaSlug, index, buttonElement) {
        // Update aria-pressed states
        const allButtons = document.querySelectorAll('#areaItems button[data-area]');
        allButtons.forEach(btn => {
            btn.setAttribute('aria-pressed', 'false');
        });
        buttonElement.setAttribute('aria-pressed', 'true');

        // Update knob position
        updateKnobPosition(index);

        // Show loading
        showLoading();

        // Simulate loading delay for smooth transition
        setTimeout(() => {
            currentArea = areaSlug;
            renderTable(areaSlug);
            hideLoading();
        }, 300);
    }

    /**
     * Show loading state
     */
    function showLoading() {
        tableContent.classList.add('hidden');
        loadingState.classList.remove('hidden');
    }

    /**
     * Hide loading state
     */
    function hideLoading() {
        loadingState.classList.add('hidden');
        tableContent.classList.remove('hidden');
    }

    /**
     * Render availability table for specific area
     * @param {string} areaSlug - The area slug
     */
    function renderTable(areaSlug) {
        const units = sortUnitsForDisplay(areaUnitsData[areaSlug] || []);
        const availability = availabilityData[areaSlug] || {};

        // Clear existing table
        tableBody.innerHTML = '';

        // Render table header with units
        renderTableHeader(units);

        // Generate date range for selected month and year
        const dates = generateDateRangeForMonth(currentYear, currentMonth);

        // Render each date row
        dates.forEach(dateInfo => {
            const row = createDateRow(dateInfo, units, availability);
            tableBody.appendChild(row);
        });

        // Update last update time
        updateLastUpdateTime();
    }

    /**
     * Render table header with unit columns
     * @param {Array} units - Array of units for the area
     */
    function renderTableHeader(units) {
        const thead = tableContent.querySelector('thead tr');
        
        // Remove existing unit headers (keep only Day & Date column)
        const existingHeaders = thead.querySelectorAll('th:not(:first-child)');
        existingHeaders.forEach(header => header.remove());

        // Add unit headers
        units.forEach(unit => {
            const th = document.createElement('th');
            th.className = 'px-4 py-4 text-center bg-brand-primary/95 text-white font-bold text-sm border-l border-white/10 min-w-[120px]';
            th.innerHTML = `
                <div class="flex flex-col items-center gap-1">
                    <span class="text-base">${unit.name}</span>
                    ${unit.capacity ? `<span class="text-xs opacity-75 font-normal">${unit.capacity} pax</span>` : ''}
                </div>
            `;
            thead.appendChild(th);
        });
    }

    /**
     * Create a table row for a specific date
     * @param {Object} dateInfo - Date information
     * @param {Array} units - Array of units
     * @param {Object} availability - Availability data
     * @returns {HTMLElement} Table row element
     */
    function createDateRow(dateInfo, units, availability) {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-gray-100 hover:bg-gray-50/50 transition-colors duration-150';

        // Date column (sticky)
        const dateCell = document.createElement('td');
        dateCell.className = 'sticky left-0 z-10 bg-white px-4 py-4 border-r border-gray-200';
        
        const isPastDate = dateInfo.isPast;
        const isWeekend = dateInfo.isWeekend;
        
        dateCell.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center font-extrabold
                    ${isPastDate ? 'bg-gray-200 text-gray-500' : (isWeekend ? 'bg-red-500 text-white' : 'bg-brand-primary text-white')}">
                    <span class="text-xl">${dateInfo.day}</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-900 text-sm">${dateInfo.dayName}</span>
                    <span class="text-xs text-gray-500 font-medium">${dateInfo.dateStr}</span>
                </div>
            </div>
        `;
        tr.appendChild(dateCell);

        // Unit availability columns
        units.forEach(unit => {
            const cell = document.createElement('td');
            cell.className = 'px-3 py-3 text-center border-l border-gray-50';
            
            const dateKey = dateInfo.dateKey;
            const unitAvailability = availability[dateKey]?.[unit.id];
            
            const cellContent = renderAvailabilityCell(unitAvailability, isPastDate);
            cell.innerHTML = cellContent;
            
            tr.appendChild(cell);
        });

        return tr;
    }

    /**
     * Render availability cell content
     * @param {string|null} status - Availability status
     * @param {boolean} isPast - Whether the date is in the past
     * @returns {string} HTML content for the cell
     */
    function renderAvailabilityCell(status, isPast) {
        if (isPast) {
            return `
                <div class="w-full h-14 rounded-xl bg-gray-100 border-2 border-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 text-xs font-semibold">Past</span>
                </div>
            `;
        }

        if (status === 'booked') {
            return `
                <div class="w-full h-14 rounded-xl bg-red-500 border-2 border-red-600 flex items-center justify-center shadow-sm transition-all duration-200" title="Not available"></div>
            `;
        }

        if (status === 'special') {
            return `
                <div class="w-full h-14 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 border-2 border-yellow-500 flex items-center justify-center shadow-sm hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer group"
                    title="Special price"></div>
            `;
        }

        // Default: available
        return `
            <div class="w-full h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 border-2 border-green-600 flex items-center justify-center shadow-sm hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer group"
                title="Available"></div>
        `;
    }

    /**
     * Sort units by numeric order in name (e.g., Deck 1..9), then by name
     * @param {Array} units
     * @returns {Array}
     */
    function sortUnitsForDisplay(units) {
        return [...units].sort((a, b) => {
            const nameA = a?.name || '';
            const nameB = b?.name || '';
            const numA = extractFirstNumber(nameA);
            const numB = extractFirstNumber(nameB);

            if (numA !== numB) return numA - numB;
            return nameA.localeCompare(nameB, undefined, { numeric: true, sensitivity: 'base' });
        });
    }

    function extractFirstNumber(text) {
        const match = String(text).match(/\d+/);
        return match ? Number.parseInt(match[0], 10) : Number.POSITIVE_INFINITY;
    }

    /**
     * Generate array of dates for specific month and year
     * @param {number} year - Year
     * @param {number} month - Month (0-11)
     * @returns {Array} Array of date objects
     */
    function generateDateRangeForMonth(year, month) {
        const dates = [];
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Get the number of days in the specified month
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Generate all dates for the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);

            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            dates.push({
                date: date,
                day: date.getDate(),
                dayName: dayNames[date.getDay()],
                dateStr: `${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()}`,
                dateKey: formatDateKey(date),
                isPast: date < today,
                isWeekend: date.getDay() === 0 || date.getDay() === 6
            });
        }

        return dates;
    }

    /**
     * Format date as YYYY-MM-DD for use as key
     * @param {Date} date - Date object
     * @returns {string} Formatted date string
     */
    function formatDateKey(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    /**
     * Update last update time display
     */
    function updateLastUpdateTime() {
        const now = new Date();
        const formatted = now.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        if (lastUpdate) {
            lastUpdate.textContent = formatted;
        }
    }

    // Handle window resize to recalculate knob position
    window.addEventListener('resize', function() {
        const activeButton = document.querySelector('#areaItems button[aria-pressed="true"]');
        if (activeButton) {
            const buttons = document.querySelectorAll('#areaItems button[data-area]');
            const index = Array.from(buttons).indexOf(activeButton);
            if (index !== -1) {
                updateKnobPosition(index);
            }
        }
    });

    // Initialize on page load
    init();
});
