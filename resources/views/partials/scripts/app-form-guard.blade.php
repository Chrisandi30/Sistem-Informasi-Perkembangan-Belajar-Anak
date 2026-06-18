@php
    // View: resources/views/partials/scripts/app-form-guard.blade.php
@endphp
<script>
    (function () {
        const state = window.__tkWinfieldFormGuard || (window.__tkWinfieldFormGuard = {
            activeForm: null,
            skipGuard: false,
            confirmOpen: false,
        });

        const normalizeFieldValue = (field) => {
            if (field instanceof HTMLInputElement) {
                if (field.type === 'checkbox' || field.type === 'radio') {
                    return field.checked ? '1' : '0';
                }

                if (field.type === 'file') {
                    return field.files && field.files.length ? `file:${field.files.length}` : '';
                }
            }

            return field.value ?? '';
        };

        const collectFormSignature = (form) => {
            return Array.from(form.querySelectorAll('input, select, textarea'))
                .filter((field) => field.name || field.hasAttribute('wire:model') || field.hasAttribute('wire:model.defer') || field.hasAttribute('wire:model.live'))
                .map((field) => {
                    const key = field.name || field.getAttribute('wire:model') || field.getAttribute('wire:model.defer') || field.getAttribute('wire:model.live') || '';
                    return `${key}:${normalizeFieldValue(field)}`;
                })
                .join('|');
        };

        const refreshGuardForm = () => {
            state.activeForm = document.querySelector('form.form-shell');
            if (!state.activeForm) {
                return;
            }

            state.activeForm.dataset.initialSignature = collectFormSignature(state.activeForm);
            state.activeForm.dataset.dirty = '0';
            delete state.activeForm.dataset.submitting;
        };

        const updateDirtyState = () => {
            if (!state.activeForm) {
                return;
            }

            const initialSignature = state.activeForm.dataset.initialSignature ?? '';
            const currentSignature = collectFormSignature(state.activeForm);
            state.activeForm.dataset.dirty = currentSignature === initialSignature ? '0' : '1';
        };

        const isFormDirty = () => {
            if (!state.activeForm) {
                return false;
            }

            if (state.activeForm.dataset.submitting === '1') {
                return false;
            }

            updateDirtyState();
            return state.activeForm.dataset.dirty === '1';
        };

        const shouldGuardNavigationLink = (link, event) => {
            if (!link) {
                return false;
            }

            if (event && (event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey)) {
                return false;
            }

            if (link.target && link.target !== '_self') {
                return false;
            }

            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
                return false;
            }

            if (link.classList.contains('btn-cancel')) {
                return false;
            }

            if (link.classList.contains('no-loading') || link.classList.contains('pdf-download') || link.hasAttribute('download')) {
                return false;
            }

            const normalizedHref = href.toLowerCase();
            if (normalizedHref.includes('pdf') || normalizedHref.includes('export')) {
                return false;
            }

            return true;
        };

        const confirmLeaveUnsaved = (onConfirm) => {
            if (!isFormDirty()) {
                onConfirm();
                return;
            }

            if (state.confirmOpen) {
                return;
            }

            state.confirmOpen = true;

            Swal.fire({
                icon: 'warning',
                title: 'Tinggalkan form?',
                text: 'Perubahan yang belum disimpan akan hilang.',
                showCancelButton: true,
                confirmButtonText: 'Ya, tinggalkan',
                cancelButtonText: 'Tetap di sini',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#2563eb',
            }).then((result) => {
                state.confirmOpen = false;

                if (!result.isConfirmed) {
                    return;
                }

                state.skipGuard = true;
                setTimeout(() => {
                    if (window.tkWinfieldUi && typeof window.tkWinfieldUi.showLoading === 'function') {
                        window.tkWinfieldUi.showLoading('Memuat halaman...');
                    }

                    requestAnimationFrame(() => {
                        requestAnimationFrame(onConfirm);
                    });
                }, 120);
            });
        };

        window.tkWinfieldUi = {
            ...(window.tkWinfieldUi || {}),
            allowNextLeave: () => { state.skipGuard = true; },
            refreshGuardForm,
            isFormDirty,
            isLeaveConfirmOpen: () => state.confirmOpen,
            shouldHoldNavigationLoading: () => false,
        };

        window.addEventListener('beforeunload', (event) => {
            if (state.skipGuard || state.confirmOpen || !isFormDirty()) {
                return;
            }

            event.preventDefault();
            event.returnValue = '';
        });

        document.addEventListener('livewire:navigated', () => {
            state.skipGuard = false;
            refreshGuardForm();
        });

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (!form.classList.contains('form-shell')) {
                return;
            }

            form.dataset.submitting = '1';
            state.skipGuard = true;
        }, true);

        document.addEventListener('input', function (event) {
            if (!state.activeForm || !event.target.closest('form.form-shell')) {
                return;
            }

            updateDirtyState();
        }, true);

        document.addEventListener('change', function (event) {
            if (!state.activeForm || !event.target.closest('form.form-shell')) {
                return;
            }

            updateDirtyState();
        }, true);

        document.addEventListener('DOMContentLoaded', refreshGuardForm);
        refreshGuardForm();
    })();
</script>
