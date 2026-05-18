/**
 * Dropdown Component
 * Handles desktop and mobile dropdown menus
 */

export class Dropdown {
    /**
     * @param {Object} options
     * @param {string} options.name - Dropdown identifier
     * @param {boolean} options.isMobile - Mobile dropdown mode
     */
    constructor(options = {}) {
        this.name = options.name;
        this.isMobile = options.isMobile || false;
        this.isOpen = false;

        const prefix = this.isMobile ? 'mobile-' : '';
        const suffix = this.isMobile ? '' : '-dropdown';

        this.btn = document.getElementById(`${prefix}${this.name}${suffix}-btn`) ||
                   document.getElementById(`${prefix}${this.name}-button`);
        this.menu = document.getElementById(`${prefix}${this.name}${suffix}-menu`) ||
                   document.getElementById(`${prefix}${this.name}-menu`);
        this.icon = document.getElementById(`${prefix}${this.name}${suffix}-icon`) ||
                   document.getElementById(`${prefix}${this.name}-icon`);
    }

    /**
     * Initialize with optional other dropdowns to close
     * @param {Dropdown[]} otherDropdowns 
     */
    init(otherDropdowns = []) {
        if (!this.btn || !this.menu) return this;

        this.btn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
            otherDropdowns.forEach(dropdown => dropdown.close());
        });

        return this;
    }

    /**
     * Toggle dropdown state
     */
    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    /**
     * Open dropdown with animation
     */
    open() {
        if (!this.menu) return;

        this.menu.classList.remove('opacity-0', 'invisible', '-translate-y-2', 'scale-95', 'hidden');
        this.menu.classList.add('opacity-100', 'visible', 'translate-y-0', 'scale-100');
        
        if (this.icon) {
            this.icon.style.transform = 'rotate(180deg)';
        }
        
        this.isOpen = true;
    }

    /**
     * Close dropdown with animation
     */
    close() {
        if (!this.menu) return;

        this.menu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
        this.menu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
        
        if (this.icon) {
            this.icon.style.transform = 'rotate(0deg)';
        }
        
        this.isOpen = false;
    }
}

/**
 * Initialize multiple dropdowns with mutual exclusion
 * @param {string[]} names - Dropdown names
 * @param {boolean} isMobile - Mobile mode
 * @returns {Dropdown[]}
 */
export function initDropdowns(names, isMobile = false) {
    const dropdowns = names.map(name => new Dropdown({ name, isMobile }));
    
    dropdowns.forEach((dropdown, index) => {
        const others = dropdowns.filter((_, i) => i !== index);
        dropdown.init(others);
    });

    return dropdowns;
}

export default Dropdown;
