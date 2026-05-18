(function() {
    'use strict';

    // Wait for DOM to be fully loaded
    function initNavbar() {
        // ============================================
        // DESKTOP DROPDOWNS
        // ============================================
        const desktopDropdowns = document.querySelectorAll('.dropdown-wrapper');
        const desktopSubmenus = document.querySelectorAll('.dropdown-submenu-wrapper');

        const closeAllSubmenus = () => {
            desktopSubmenus.forEach(wrapper => {
                const submenu = wrapper.querySelector('.dropdown-submenu');
                const icon = wrapper.querySelector('.dropdown-submenu-icon');
                if (submenu) {
                    submenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                    submenu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                    submenu.style.left = '';
                    submenu.style.right = '';
                    submenu.style.marginLeft = '';
                    submenu.style.marginRight = '';
                }
                if (icon) icon.style.transform = 'rotate(0deg)';
                wrapper.dataset.open = 'false';
            });
        };

        const positionSubmenu = (submenu) => {
            if (!submenu) return;

            submenu.style.left = '100%';
            submenu.style.right = 'auto';
            submenu.style.marginLeft = '0.5rem';
            submenu.style.marginRight = '0';

            const rect = submenu.getBoundingClientRect();
            if (rect.right > window.innerWidth) {
                submenu.style.left = 'auto';
                submenu.style.right = '100%';
                submenu.style.marginLeft = '0';
                submenu.style.marginRight = '0.5rem';
            }
        };

        desktopDropdowns.forEach(wrapper => {
            const btn = wrapper.querySelector('.dropdown-btn');
            const menu = wrapper.querySelector('.dropdown-menu');
            const icon = wrapper.querySelector('.dropdown-icon');
            let isOpen = false;

            if (!btn || !menu) return;

            // Toggle dropdown
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                // Close other dropdowns
                desktopDropdowns.forEach(other => {
                    if (other !== wrapper) {
                        const otherMenu = other.querySelector('.dropdown-menu');
                        const otherIcon = other.querySelector('.dropdown-icon');
                        if (otherMenu) {
                            otherMenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                            otherMenu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                        }
                        if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                    }
                });

                // Toggle current
                isOpen = !isOpen;
                if (isOpen) {
                    menu.classList.remove('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                    menu.classList.add('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    menu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                    menu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                    closeAllSubmenus();
                }
            });
        });

        // Desktop submenu (Cabin)
        desktopSubmenus.forEach(wrapper => {
            const btn = wrapper.querySelector('.dropdown-submenu-btn');
            const submenu = wrapper.querySelector('.dropdown-submenu');
            const icon = wrapper.querySelector('.dropdown-submenu-icon');

            if (!btn || !submenu) return;

            wrapper.dataset.open = 'false';

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = wrapper.dataset.open === 'true';
                closeAllSubmenus();

                if (!isOpen) {
                    positionSubmenu(submenu);
                    submenu.classList.remove('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                    submenu.classList.add('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                    if (icon) icon.style.transform = 'rotate(90deg)';
                    wrapper.dataset.open = 'true';
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            desktopDropdowns.forEach(wrapper => {
                const menu = wrapper.querySelector('.dropdown-menu');
                const icon = wrapper.querySelector('.dropdown-icon');
                if (menu) {
                    menu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'scale-100');
                    menu.classList.add('opacity-0', 'invisible', '-translate-y-2', 'scale-95');
                }
                if (icon) icon.style.transform = 'rotate(0deg)';
            });
            closeAllSubmenus();
        });

        // ============================================
        // MOBILE MENU
        // ============================================
        const mobileMenuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        let mobileMenuOpen = false;

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                mobileMenuOpen = !mobileMenuOpen;

                if (mobileMenuOpen) {
                    // Open menu
                    mobileMenu.classList.remove('max-h-0', 'opacity-0', 'scale-y-0');
                    mobileMenu.classList.add('max-h-screen', 'opacity-100', 'scale-y-100');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    // Close menu
                    mobileMenu.classList.remove('max-h-screen', 'opacity-100', 'scale-y-100');
                    mobileMenu.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target) && mobileMenuOpen) {
                    mobileMenuOpen = false;
                    mobileMenu.classList.remove('max-h-screen', 'opacity-100', 'scale-y-100');
                    mobileMenu.classList.add('max-h-0', 'opacity-0', 'scale-y-0');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });
        }

        // ============================================
        // MOBILE DROPDOWNS
        // ============================================
        const mobileDropdowns = document.querySelectorAll('.mobile-dropdown-wrapper');
        const mobileSubdropdowns = document.querySelectorAll('.mobile-subdropdown-wrapper');

        mobileDropdowns.forEach(wrapper => {
            const btn = wrapper.querySelector('.mobile-dropdown-btn');
            const menu = wrapper.querySelector('.mobile-dropdown-menu');
            const icon = wrapper.querySelector('.mobile-dropdown-icon');
            let isOpen = false;

            if (!btn || !menu) return;

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                isOpen = !isOpen;

                if (isOpen) {
                    // Open submenu
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                    menu.classList.remove('opacity-0');
                    menu.classList.add('opacity-100');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    // Close submenu
                    menu.style.maxHeight = '0';
                    menu.classList.remove('opacity-100');
                    menu.classList.add('opacity-0');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Mobile subdropdowns (Cabin)
        mobileSubdropdowns.forEach(wrapper => {
            const btn = wrapper.querySelector('.mobile-subdropdown-btn');
            const menu = wrapper.querySelector('.mobile-subdropdown-menu');
            const icon = wrapper.querySelector('.mobile-subdropdown-icon');
            const parentMenu = wrapper.closest('.mobile-dropdown-menu');
            let isOpen = false;

            const updateParentMenuHeight = () => {
                if (!parentMenu) return;
                parentMenu.style.maxHeight = 'none';
                const fullHeight = parentMenu.scrollHeight;
                parentMenu.style.maxHeight = fullHeight + 'px';
            };

            if (!btn || !menu) return;

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                isOpen = !isOpen;

                if (isOpen) {
                    menu.classList.remove('hidden', 'max-h-0', 'opacity-0');
                    menu.classList.add('opacity-100');
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                    updateParentMenuHeight();
                    if (icon) icon.style.transform = 'rotate(180deg)';
                } else {
                    menu.style.maxHeight = '0';
                    menu.classList.remove('opacity-100');
                    menu.classList.add('opacity-0');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                    setTimeout(() => {
                        menu.classList.add('hidden', 'max-h-0');
                        updateParentMenuHeight();
                    }, 300);
                }
            });
        });

        // ============================================
        // SCROLL-BASED NAVBAR VISIBILITY
        // ============================================
        initScrollNavbar();
    }

    /**
     * Initialize scroll-based navbar visibility
     * Navbar hidden at top, shows when scrolling down
     * Only applies on home/dashboard page (data-scroll-navbar="true")
     */
    function initScrollNavbar() {
        const navbar = document.getElementById('main-navbar');
        if (!navbar) return;

        // Only apply scroll behavior if data-scroll-navbar is true (home page only)
        const enableScrollNavbar = navbar.dataset.scrollNavbar === 'true';
        if (!enableScrollNavbar) return;

        let lastScrollY = window.scrollY;
        let ticking = false;
        const scrollThreshold = 50; // Minimum scroll before showing navbar
        let isNavbarVisible = false;

        /**
         * Update navbar visibility based on scroll position
         */
        function updateNavbar() {
            const currentScrollY = window.scrollY;
            const shouldShow = currentScrollY > scrollThreshold;

            if (shouldShow && !isNavbarVisible) {
                // Show navbar when scrolled past threshold
                navbar.classList.remove('-translate-y-full');
                navbar.classList.add('translate-y-0');
                isNavbarVisible = true;
                
                // Dispatch custom event for wave divider sync
                window.dispatchEvent(new CustomEvent('navbarVisibilityChange', { 
                    detail: { visible: true } 
                }));
            } else if (!shouldShow && isNavbarVisible) {
                // Hide navbar when at top
                navbar.classList.remove('translate-y-0');
                navbar.classList.add('-translate-y-full');
                isNavbarVisible = false;
                
                // Dispatch custom event for wave divider sync
                window.dispatchEvent(new CustomEvent('navbarVisibilityChange', { 
                    detail: { visible: false } 
                }));
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        /**
         * Throttled scroll handler using requestAnimationFrame
         */
        function onScroll() {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }

        // Listen for scroll events
        window.addEventListener('scroll', onScroll, { passive: true });

        // Initial check on page load
        updateNavbar();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbar);
    } else {
        initNavbar();
    }

})();
