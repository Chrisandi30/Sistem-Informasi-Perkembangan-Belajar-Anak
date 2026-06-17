<script>
    (function () {
        const authMeta = document.getElementById('appAuthMeta');
        const parseJsonAttribute = (value, fallback) => {
            if (typeof value !== 'string' || value === '') {
                return fallback;
            }

            try {
                const parsed = JSON.parse(value);
                return parsed ?? fallback;
            } catch (error) {
                return fallback;
            }
        };

        const loginSuccessMessage = authMeta ? parseJsonAttribute(authMeta.dataset.success, '') : '';
        const loginErrors = authMeta ? parseJsonAttribute(authMeta.dataset.errors, []) : [];
        const loginForm = document.querySelector('.auth-login-form');
        const loginOverlay = document.getElementById('appLoadingOverlay');
        const loginLoadingText = document.getElementById('appLoadingText');
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        const showAuthLoading = (message = 'Sedang memproses...') => {
            if (loginLoadingText) {
                loginLoadingText.textContent = message;
            }

            if (loginOverlay) {
                loginOverlay.classList.add('show');
            }

            document.body.classList.add('page-loading');
        };

        const hideAuthLoading = () => {
            if (loginOverlay) {
                loginOverlay.classList.remove('show');
            }

            document.body.classList.remove('page-loading');
        };

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (form.classList.contains('logout-form')) {
                event.preventDefault();

                Swal.fire({
                    icon: 'question',
                    title: 'Keluar dari sistem?',
                    text: 'Sesi Anda akan diakhiri.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, keluar',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#dc2626',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    if (window.tkWinfieldUi && typeof window.tkWinfieldUi.showLoading === 'function') {
                        window.tkWinfieldUi.showLoading('Sedang keluar...');
                    }

                    form.submit();
                });
                return;
            }

            if (form.classList.contains('auth-login-form')) {
                showAuthLoading('Sedang masuk...');
            }
        }, true);

        document.addEventListener('DOMContentLoaded', () => {
            if (loginSuccessMessage && loginSuccessMessage !== 'Berhasil keluar dari sistem.') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: loginSuccessMessage,
                    confirmButtonColor: '#2563eb',
                    timer: 900,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            }

            if (Array.isArray(loginErrors) && loginErrors.length) {
                document.querySelectorAll('.error-box').forEach((box) => { box.style.display = 'none'; });
                Swal.fire({
                    icon: 'error',
                    title: 'Login gagal',
                    text: loginErrors[0],
                    confirmButtonColor: '#dc2626',
                });
            }

            if (toggleBtn && passwordInput) {
                const icon = toggleBtn.querySelector('i');
                toggleBtn.addEventListener('click', function () {
                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';

                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
        });

        window.addEventListener('pageshow', () => {
            if (loginForm) {
                hideAuthLoading();
            }
        });

        if (loginForm && !loginForm.dataset.authBound) {
            loginForm.dataset.authBound = 'true';
            loginForm.addEventListener('submit', () => {
                showAuthLoading('Sedang masuk...');
            });
        }
    })();
</script>