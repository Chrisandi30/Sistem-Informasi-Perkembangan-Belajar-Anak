<script>
    (function () {
        const state = window.__tkWinfieldPageFeedback || (window.__tkWinfieldPageFeedback = {
            bound: false,
            observerBound: false,
            livewireHookBound: false,
            lastSignature: null,
            pendingDelete: null,
        });

        // Jalankan proses getAlertText pada halaman.
        const getAlertText = (selector) => {
            const element = document.querySelector(selector);
            return element ? element.textContent.trim() : '';
        };

        // Jalankan proses getValidationErrors pada halaman.
        const getValidationErrors = () => Array.from(document.querySelectorAll('.error-list li'))
            .map((item) => item.textContent.trim())
            .filter(Boolean);

        // Jalankan proses hasErrorState pada halaman.
        const hasErrorState = () => Boolean(getAlertText('.alert-danger') || getValidationErrors().length);

        // Jalankan proses renderFeedback pada halaman.
        const renderFeedback = (type, message, options = {}) => {
            if (!message) {
                return;
            }

            const title = options.title || (type === 'success' ? 'Berhasil' : 'Terjadi masalah');
            const confirmButtonColor = options.confirmButtonColor || (type === 'success' ? '#2563eb' : '#dc2626');
            const requestedTimer = options.timer ?? null;

            if (type === 'success') {
                if (message === 'Berhasil keluar dari sistem.') {
                    return;
                }

                const authSuccessMessages = [
                    'Login berhasil. Selamat menggunakan sistem.',
                ];
                const timer = requestedTimer ?? (authSuccessMessages.includes(message) ? 900 : 1100);
                const showConfirmButton = options.showConfirmButton ?? false;
                const timerProgressBar = options.timerProgressBar ?? true;

                Swal.fire({
                    icon: 'success',
                    title,
                    text: message,
                    confirmButtonColor,
                    timer: timer ?? (authSuccessMessages.includes(message) ? 900 : 1100),
                    showConfirmButton,
                    timerProgressBar,
                });
                return;
            }

            if (type === 'error') {
                const timer = requestedTimer;
                const showConfirmButton = options.showConfirmButton ?? (timer ? false : true);
                const timerProgressBar = options.timerProgressBar ?? Boolean(timer);

                Swal.fire({
                    icon: 'error',
                    title,
                    text: message,
                    confirmButtonColor,
                    timer,
                    showConfirmButton,
                    timerProgressBar,
                });
            }
        };

        // Jalankan proses resolvePendingDelete pada halaman.
        const resolvePendingDelete = (forceSuccess = false) => {
            if (!state.pendingDelete) {
                return;
            }

            if (hasErrorState()) {
                state.pendingDelete = null;
                return;
            }

            if (forceSuccess) {
                renderFeedback('success', state.pendingDelete.message || 'Data berhasil dihapus.');
                state.pendingDelete = null;
            }
        };

        // Jalankan proses showPageFeedback pada halaman.
        const showPageFeedback = () => {
            const currentSuccessMessage = getAlertText('.alert-success');
            const currentErrorMessage = getAlertText('.alert-danger');
            const currentValidationErrors = getValidationErrors();

            const signature = JSON.stringify({
                success: currentSuccessMessage,
                error: currentErrorMessage,
                errors: currentValidationErrors,
            });

            if (signature === state.lastSignature) {
                return;
            }

            state.lastSignature = signature;

            document.querySelectorAll('.alert-danger, .alert-success').forEach((alert) => {
                alert.style.display = 'none';
            });

            if (currentSuccessMessage) {
                renderFeedback('success', currentSuccessMessage);
                state.pendingDelete = null;
                return;
            }

            if (currentErrorMessage) {
                renderFeedback('error', currentErrorMessage);
                state.pendingDelete = null;
                return;
            }

            if (currentValidationErrors.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data belum lengkap',
                    html: '<div style="text-align:left;line-height:1.6;">' + currentValidationErrors.map((error) => '&bull; ' + error).join('<br>') + '</div>',
                    confirmButtonColor: '#dc2626',
                });
                state.pendingDelete = null;
            }
        };

        // Jalankan proses bindLivewireHooks pada halaman.
        const bindLivewireHooks = () => {
            if (state.livewireHookBound || !window.Livewire || typeof window.Livewire.hook !== 'function') {
                return;
            }

            state.livewireHookBound = true;

            window.Livewire.hook('request', ({ succeed, fail }) => {
                succeed(() => {
                    queueMicrotask(() => {
                        showPageFeedback();
                        if (state.pendingDelete && !hasErrorState()) {
                            resolvePendingDelete(true);
                        }
                    });
                });

                fail(() => {
                    state.pendingDelete = null;
                });
            });
        };

        // Jalankan proses bindObserver pada halaman.
        const bindObserver = () => {
            if (state.observerBound) {
                return;
            }

            const observerTarget = document.querySelector('.content-inner');
            if (!observerTarget) {
                return;
            }

            state.observerBound = true;
            const observer = new MutationObserver(() => {
                queueMicrotask(showPageFeedback);
            });

            observer.observe(observerTarget, { childList: true, subtree: true, characterData: true });
        };

        if (!state.bound) {
            state.bound = true;

            document.addEventListener('click', function (event) {
                const cancelLink = event.target.closest('a.btn-cancel');
                if (cancelLink) {
                    if (cancelLink.classList.contains('no-cancel-confirm')) {
                        return;
                    }

                    const href = cancelLink.getAttribute('href');
                    if (!href) {
                        return;
                    }

                    // Jalankan proses goToCancelTarget pada halaman.
                    const goToCancelTarget = () => {
                        if (window.tkWinfieldUi && typeof window.tkWinfieldUi.allowNextLeave === 'function') {
                            window.tkWinfieldUi.allowNextLeave();
                        }

                        if (window.tkWinfieldUi && typeof window.tkWinfieldUi.skipNextNavigateLoading === 'function') {
                            window.tkWinfieldUi.skipNextNavigateLoading();
                        }

                        if (window.tkWinfieldUi && typeof window.tkWinfieldUi.hideLoading === 'function') {
                            window.tkWinfieldUi.hideLoading();
                        }

                        if (window.Livewire && typeof window.Livewire.navigate === 'function') {
                            window.Livewire.navigate(href);
                            return;
                        }

                        window.location.href = href;
                    };

                    if (window.tkWinfieldUi && typeof window.tkWinfieldUi.isFormDirty === 'function' && !window.tkWinfieldUi.isFormDirty()) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        goToCancelTarget();
                        return;
                    }

                    event.preventDefault();
                    event.stopImmediatePropagation();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Batalkan perubahan?',
                        text: 'Perubahan yang belum disimpan tidak akan disimpan.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Tetap di halaman',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#2563eb',
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            return;
                        }

                        goToCancelTarget();
                    });
                    return;
                }

                const deleteButton = event.target.closest('button.btn-delete');

                if (!deleteButton) {
                    return;
                }

                if (deleteButton.dataset.deleteConfirmed === '1') {
                    delete deleteButton.dataset.deleteConfirmed;
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus data?',
                    text: 'Data yang sudah dihapus tidak dapat dikembalikan.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#2563eb',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    state.pendingDelete = {
                        message: deleteButton.dataset.deleteSuccess || 'Data berhasil dihapus.',
                    };

                    deleteButton.dataset.deleteConfirmed = '1';
                    deleteButton.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
                });
            }, true);

            window.addEventListener('app-feedback', (event) => {
                const detail = event.detail || {};
                renderFeedback(detail.type || 'success', detail.message || '', detail);
                if (detail.type === 'error') {
                    state.pendingDelete = null;
                }
            });

            document.addEventListener('livewire:init', () => {
                bindLivewireHooks();
                bindObserver();
            });

            document.addEventListener('livewire:initialized', () => {
                bindLivewireHooks();
                bindObserver();
            });
        }

        document.addEventListener('DOMContentLoaded', showPageFeedback);
        document.addEventListener('livewire:navigated', showPageFeedback);
    })();
</script>
