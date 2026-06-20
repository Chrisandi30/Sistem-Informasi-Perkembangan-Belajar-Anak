<script>
    (function () {
        const loginUrl = @json(route('login'));
        const loadingOverlay = document.getElementById('appLoadingOverlay');
        const loadingText = document.getElementById('appLoadingText');
        let livewireHookBound = false;
        let skipNextNavigateLoading = false;

        // Jalankan proses showLoading pada halaman.
        const showLoading = (message = 'Memuat halaman...') => {
            if (loadingText) {
                loadingText.textContent = message;
            }

            if (loadingOverlay) {
                loadingOverlay.classList.add('show');
            }

            document.body.classList.add('page-loading');
        };

        // Jalankan proses hideLoading pada halaman.
        const hideLoading = () => {
            if (loadingOverlay) {
                loadingOverlay.classList.remove('show');
            }

            document.body.classList.remove('page-loading');
        };

        // Jalankan proses redirectExpiredSession pada halaman.
        const redirectExpiredSession = () => {
            hideLoading();

            if (window.location.href !== loginUrl) {
                window.location.replace(loginUrl);
            }
        };

        // Tangkap status 419 dari seluruh request fetch, termasuk request internal Livewire.
        if (!window.__tkSessionFetchBound && typeof window.fetch === 'function') {
            window.__tkSessionFetchBound = true;
            const nativeFetch = window.fetch.bind(window);

            window.fetch = async (...args) => {
                const response = await nativeFetch(...args);

                if (response.status === 419) {
                    redirectExpiredSession();
                }

                return response;
            };
        }

        // Jalankan proses navigateWithLoading pada halaman.
        const navigateWithLoading = (link) => {
            const href = link.getAttribute('href');
            if (!href) {
                return;
            }

            showLoading('Memuat halaman...');

            if (link.hasAttribute('wire:navigate') && window.Livewire && typeof window.Livewire.navigate === 'function') {
                window.Livewire.navigate(href);
                return;
            }

            window.location.href = href;
        };


        // Jalankan proses initPasswordVisibilityToggle pada halaman.
        const initPasswordVisibilityToggle = () => {
            if (document.documentElement.dataset.passwordToggleBound === '1') {
                return;
            }

            document.documentElement.dataset.passwordToggleBound = '1';
            document.addEventListener('click', function (event) {
                const button = event.target.closest('[data-password-toggle]');
                if (! button) return;

                const wrapper = button.closest('.relative');
                const input = wrapper ? wrapper.querySelector('[data-password-field]') : null;
                const icon = button.querySelector('i');
                if (! input || ! icon) return;

                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                icon.classList.toggle('fa-eye', ! isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan password akun' : 'Tampilkan password akun');
            });
        };
        // Jalankan proses bindLivewireHooks pada halaman.
        const bindLivewireHooks = () => {
            if (livewireHookBound || !window.Livewire || typeof window.Livewire.hook !== 'function') {
                return;
            }

            livewireHookBound = true;

            window.Livewire.hook('request', ({ succeed, fail }) => {
                succeed(() => {
                    hideLoading();
                });

                fail(({ status, preventDefault }) => {
                    hideLoading();

                    if (status === 419) {
                        preventDefault();
                        redirectExpiredSession();
                    }
                });
            });
        };

        window.tkWinfieldUi = {
            ...(window.tkWinfieldUi || {}),
            showLoading,
            hideLoading,
            navigateWithLoading,
            skipNextNavigateLoading: () => { skipNextNavigateLoading = true; },
        };

        initPasswordVisibilityToggle();

        window.addEventListener('pageshow', hideLoading);

        document.addEventListener('livewire:init', bindLivewireHooks);
        document.addEventListener('livewire:initialized', bindLivewireHooks);
        document.addEventListener('livewire:navigated', () => {
            bindLivewireHooks();
            hideLoading();
        });
        document.addEventListener('livewire:navigate', () => {
            if (window.tkWinfieldUi && typeof window.tkWinfieldUi.shouldHoldNavigationLoading === 'function' && window.tkWinfieldUi.shouldHoldNavigationLoading()) {
                hideLoading();
                return;
            }

            if (skipNextNavigateLoading) {
                skipNextNavigateLoading = false;
                hideLoading();
                return;
            }

            showLoading('Memuat halaman...');
        });

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (form.classList.contains('logout-form')) {
                return;
            }

            showLoading('Sedang memproses data...');
        }, true);

        document.addEventListener('click', function (event) {
            const link = event.target.closest('a[href]');
            if (!link) {
                return;
            }

            if (event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
                return;
            }

            if (link.target && link.target !== '_self') {
                return;
            }

            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
                return;
            }

            if (link.classList.contains('btn-cancel')) {
                return;
            }

            if (link.closest('#guruSiswaResults .pagination')) {
                return;
            }

            if (link.classList.contains('no-loading') || link.classList.contains('pdf-download') || link.hasAttribute('download')) {
                return;
            }

            const normalizedHref = href.toLowerCase();
            if (normalizedHref.includes('pdf') || normalizedHref.includes('export')) {
                return;
            }

            if (window.tkWinfieldUi && typeof window.tkWinfieldUi.shouldHoldNavigationLoading === 'function' && window.tkWinfieldUi.shouldHoldNavigationLoading(link)) {
                hideLoading();
                return;
            }

            showLoading('Memuat halaman...');
        }, true);
    })();

</script>
