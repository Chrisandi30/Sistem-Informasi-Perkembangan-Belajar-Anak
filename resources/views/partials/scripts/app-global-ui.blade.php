@php
    // View: resources/views/partials/scripts/app-global-ui.blade.php
@endphp
<script>
    (function () {
        const loadingOverlay = document.getElementById('appLoadingOverlay');
        const loadingText = document.getElementById('appLoadingText');
        let livewireHookBound = false;
        let skipNextNavigateLoading = false;

        const showLoading = (message = 'Memuat halaman...') => {
            if (loadingText) {
                loadingText.textContent = message;
            }

            if (loadingOverlay) {
                loadingOverlay.classList.add('show');
            }

            document.body.classList.add('page-loading');
        };

        const hideLoading = () => {
            if (loadingOverlay) {
                loadingOverlay.classList.remove('show');
            }

            document.body.classList.remove('page-loading');
        };

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
        const bindLivewireHooks = () => {
            if (livewireHookBound || !window.Livewire || typeof window.Livewire.hook !== 'function') {
                return;
            }

            livewireHookBound = true;

            window.Livewire.hook('request', ({ succeed, fail }) => {
                succeed(() => {
                    hideLoading();
                });

                fail(() => {
                    hideLoading();
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

        // Tangani session kedaluwarsa pada request Livewire tanpa menampilkan Page Expired.
        document.addEventListener('livewire:init', () => {
            if (!window.Livewire || typeof window.Livewire.hook !== 'function') {
                return;
            }

            window.Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status !== 419) {
                        return;
                    }

                    preventDefault();
                    window.location.href = '{{ route('login') }}';
                });
            });
        });
</script>
