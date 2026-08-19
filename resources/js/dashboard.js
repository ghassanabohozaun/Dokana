/**
 * DOKANA ENTERPRISE DASHBOARD - TAILWIND JS CORE
 * 100% Standalone, High Performance, Zero External Dependencies
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarToggle();
        initDropdowns();
        initDarkMode();
        initTooltipsAndPopovers();
    });

    // 1. Sidebar Collapse & Mobile Drawer
    function initSidebarToggle() {
        const sidebar = document.getElementById('app-sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
        const collapseButton = document.getElementById('sidebar-collapse-btn');

        if (!sidebar) return;

        // Mobile drawer toggle
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const isOpen = sidebar.classList.contains('translate-x-0');
                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        });

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', closeSidebar);
        }

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full', 'translate-x-full');
            sidebar.classList.add('translate-x-0');
            if (sidebarBackdrop) {
                sidebarBackdrop.classList.remove('hidden');
                setTimeout(() => sidebarBackdrop.classList.add('opacity-100'), 10);
            }
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            const isRtl = document.documentElement.getAttribute('dir') === 'rtl' ||
                          document.documentElement.getAttribute('data-textdirection') === 'rtl';
            
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add(isRtl ? 'translate-x-full' : '-translate-x-full');
            
            if (sidebarBackdrop) {
                sidebarBackdrop.classList.remove('opacity-100');
                setTimeout(() => sidebarBackdrop.classList.add('hidden'), 200);
            }
            document.body.classList.remove('overflow-hidden');
        }

        // Desktop mini-sidebar toggle
        if (collapseButton) {
            collapseButton.addEventListener('click', () => {
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem('dokana-sidebar-collapsed', isCollapsed ? 'true' : 'false');
            });

            // Restore state
            if (localStorage.getItem('dokana-sidebar-collapsed') === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }
        }
    }

    // 2. Click-Outside Dropdowns
    function initDropdowns() {
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('[data-dropdown-toggle]');
            const openDropdowns = document.querySelectorAll('[data-dropdown-menu]:not(.hidden)');

            if (toggle) {
                const targetId = toggle.getAttribute('data-dropdown-toggle');
                const menu = document.getElementById(targetId);
                
                if (menu) {
                    const isHidden = menu.classList.contains('hidden');
                    // Close others
                    openDropdowns.forEach(d => {
                        if (d !== menu) d.classList.add('hidden');
                    });
                    
                    if (isHidden) {
                        menu.classList.remove('hidden');
                    } else {
                        menu.classList.add('hidden');
                    }
                }
                e.stopPropagation();
            } else if (!e.target.closest('[data-dropdown-menu]')) {
                openDropdowns.forEach(d => d.classList.add('hidden'));
            }
        });
    }

    // 3. Dark Mode Support
    function initDarkMode() {
        const themeToggles = document.querySelectorAll('[data-theme-toggle]');
        
        const applyTheme = (isDark) => {
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.setItem('dokana-theme', isDark ? 'dark' : 'light');
        };

        // Init theme
        const savedTheme = localStorage.getItem('dokana-theme');
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            applyTheme(true);
        }

        themeToggles.forEach(btn => {
            btn.addEventListener('click', () => {
                const isDark = document.documentElement.classList.contains('dark');
                applyTheme(!isDark);
            });
        });
    }

    // 4. Modal Helpers (Tailwind native)
    window.DokanaModal = {
        open: function (modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        },
        close: function (modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    };

    function initTooltipsAndPopovers() {
        // Fast helpers
    }

})();
