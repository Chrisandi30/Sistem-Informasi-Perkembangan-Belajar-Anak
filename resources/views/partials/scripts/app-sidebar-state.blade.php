<script>
    (function () {
        const sidebarStorageKey = 'tk-winfield-sidebar-scroll';
        const menuStoragePrefix = 'tk-winfield-menu-';

        const getSidebar = () => document.querySelector('.sidebar');
        const getSidebarOverlay = () => document.getElementById('mobileSidebarOverlay');
        const isMobileViewport = () => window.innerWidth <= 1000;

        const closeMobileSidebar = () => {
            const sidebar = getSidebar();
            const overlay = getSidebarOverlay();

            sidebar?.classList.remove('mobile-open');
            overlay?.classList.remove('show');
            document.body.style.overflow = '';
        };

        const openMobileSidebar = () => {
            const sidebar = getSidebar();
            const overlay = getSidebarOverlay();

            if (!sidebar || !overlay) {
                return;
            }

            sidebar.classList.add('mobile-open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        };

        const setMenuVisualState = (menu, expanded) => {
            const links = menu.querySelector('.dropdown-links');
            if (!links) {
                return;
            }

            if (expanded) {
                menu.open = true;
                links.style.maxHeight = 'none';
                links.style.opacity = '1';
                links.style.transform = 'translateY(0)';
                links.style.marginTop = '6px';
                return;
            }

            links.style.maxHeight = '0px';
            links.style.opacity = '0';
            links.style.transform = 'translateY(-6px)';
            links.style.marginTop = '0';
            menu.open = false;
        };

        const restoreSidebarScroll = () => {
            const sidebar = getSidebar();
            if (!sidebar) {
                return;
            }

            const savedScroll = sessionStorage.getItem(sidebarStorageKey);
            if (savedScroll !== null) {
                sidebar.scrollTop = Number(savedScroll);
            }
        };

        const persistSidebarScroll = () => {
            const sidebar = getSidebar();
            if (!sidebar) {
                return;
            }

            sessionStorage.setItem(sidebarStorageKey, String(sidebar.scrollTop));
        };

        const restoreMenuState = () => {
            document.querySelectorAll('.menu-dropdown[data-menu-key]').forEach((menu) => {
                const key = menu.getAttribute('data-menu-key');
                const savedState = sessionStorage.getItem(menuStoragePrefix + key);

                if (savedState === 'open') {
                    setMenuVisualState(menu, true);
                } else if (savedState === 'closed') {
                    setMenuVisualState(menu, false);
                } else {
                    setMenuVisualState(menu, menu.hasAttribute('open'));
                }
            });
        };

        const persistMenuState = () => {
            document.querySelectorAll('.menu-dropdown[data-menu-key]').forEach((menu) => {
                const key = menu.getAttribute('data-menu-key');
                sessionStorage.setItem(menuStoragePrefix + key, menu.open ? 'open' : 'closed');
            });
        };

        const animateMenuToggle = (menu) => {
            const links = menu.querySelector('.dropdown-links');
            if (!links || menu.dataset.animating === '1') {
                return;
            }

            const isOpening = !menu.open;
            menu.dataset.animating = '1';

            if (isOpening) {
                menu.open = true;
                links.style.maxHeight = '0px';
                links.style.opacity = '0';
                links.style.transform = 'translateY(-6px)';
                links.style.marginTop = '0';

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        links.style.maxHeight = `${links.scrollHeight}px`;
                        links.style.opacity = '1';
                        links.style.transform = 'translateY(0)';
                        links.style.marginTop = '6px';
                    });
                });
            } else {
                links.style.maxHeight = `${links.scrollHeight}px`;
                links.style.opacity = '1';
                links.style.transform = 'translateY(0)';
                links.style.marginTop = '6px';

                requestAnimationFrame(() => {
                    links.style.maxHeight = '0px';
                    links.style.opacity = '0';
                    links.style.transform = 'translateY(-6px)';
                    links.style.marginTop = '0';
                });
            }

            window.setTimeout(() => {
                if (isOpening) {
                    links.style.maxHeight = 'none';
                    menu.open = true;
                } else {
                    menu.open = false;
                }

                menu.dataset.animating = '0';
                persistMenuState();
            }, 340);
        };

        const bindAnimatedMenus = () => {
            document.querySelectorAll('.menu-dropdown[data-menu-key] > summary').forEach((summary) => {
                if (summary.dataset.menuBound === '1') {
                    return;
                }

                summary.dataset.menuBound = '1';
                summary.addEventListener('click', (event) => {
                    event.preventDefault();
                    animateMenuToggle(summary.parentElement);
                });
            });
        };

        const bindMobileSidebar = () => {
            const toggle = document.getElementById('mobileSidebarToggle');
            const innerToggle = document.getElementById('mobileSidebarInnerToggle');
            const overlay = getSidebarOverlay();

            if (toggle && toggle.dataset.sidebarBound !== '1') {
                toggle.dataset.sidebarBound = '1';
                toggle.addEventListener('click', () => {
                    const sidebar = getSidebar();

                    if (!sidebar) {
                        return;
                    }

                    if (sidebar.classList.contains('mobile-open')) {
                        closeMobileSidebar();
                    } else {
                        openMobileSidebar();
                    }
                });
            }

            if (innerToggle && innerToggle.dataset.sidebarBound !== '1') {
                innerToggle.dataset.sidebarBound = '1';
                innerToggle.addEventListener('click', () => {
                    const sidebar = getSidebar();

                    if (!sidebar) {
                        return;
                    }

                    if (sidebar.classList.contains('mobile-open')) {
                        closeMobileSidebar();
                    } else {
                        openMobileSidebar();
                    }
                });
            }

            if (overlay && overlay.dataset.sidebarBound !== '1') {
                overlay.dataset.sidebarBound = '1';
                overlay.addEventListener('click', closeMobileSidebar);
            }
        };

        document.addEventListener('livewire:navigate', () => {
            persistSidebarScroll();
            persistMenuState();
            closeMobileSidebar();
        });
        document.addEventListener('livewire:navigated', () => {
            restoreSidebarScroll();
            restoreMenuState();
            bindAnimatedMenus();
            bindMobileSidebar();
            if (!isMobileViewport()) {
                closeMobileSidebar();
            }
        });
        window.addEventListener('beforeunload', () => {
            persistSidebarScroll();
            persistMenuState();
        });
        document.addEventListener('DOMContentLoaded', () => {
            restoreSidebarScroll();
            restoreMenuState();
            bindAnimatedMenus();
            bindMobileSidebar();
        });
        window.addEventListener('resize', () => {
            if (!isMobileViewport()) {
                closeMobileSidebar();
            }
        });

        document.addEventListener('scroll', function (event) {
            if (event.target?.classList?.contains('sidebar')) {
                persistSidebarScroll();
            }
        }, true);

        document.addEventListener('click', function (event) {
            if (!isMobileViewport()) {
                return;
            }

            const link = event.target.closest('.sidebar a');
            if (link) {
                closeMobileSidebar();
            }
        }, true);

        document.addEventListener('toggle', function (event) {
            if (event.target?.classList?.contains('menu-dropdown') && event.target?.hasAttribute('data-menu-key')) {
                persistMenuState();
            }
        }, true);
    })();
</script>
