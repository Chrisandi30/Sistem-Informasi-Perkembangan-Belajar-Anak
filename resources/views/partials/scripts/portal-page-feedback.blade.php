@php
    // View: resources/views/partials/scripts/portal-page-feedback.blade.php
@endphp
<script>
    (function () {
        const state = window.__tkWinfieldPortalFeedback || (window.__tkWinfieldPortalFeedback = {
            lastSignature: null,
        });

        // Jalankan proses getSuccessMessage pada halaman.
        const getSuccessMessage = () => {
            const element = document.querySelector('.portal-alert-success');
            return element ? element.textContent.trim() : '';
        };

        // Jalankan proses getErrorItems pada halaman.
        const getErrorItems = () => Array.from(document.querySelectorAll('.portal-alert-errors li'))
            .map((item) => item.textContent.trim())
            .filter(Boolean);

        // Jalankan proses showPortalFeedback pada halaman.
        const showPortalFeedback = () => {
            const successMessage = getSuccessMessage();
            const errorItems = getErrorItems();

            const signature = JSON.stringify({
                success: successMessage,
                errors: errorItems,
            });

            if (signature === state.lastSignature) {
                return;
            }

            state.lastSignature = signature;

            document.querySelectorAll('.portal-alert-success, .portal-alert-errors').forEach((el) => {
                el.style.display = 'none';
            });

            if (successMessage && successMessage !== 'Berhasil keluar dari sistem.') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#2563eb',
                    timer: successMessage === 'Login berhasil. Selamat menggunakan sistem.' ? 900 : 1100,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
                return;
            }

            if (errorItems.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi masalah',
                    html: '<div style="text-align:left;line-height:1.6;">' + errorItems.map((error) => '&bull; ' + error).join('<br>') + '</div>',
                    confirmButtonColor: '#dc2626',
                });
            }
        };

        document.addEventListener('DOMContentLoaded', showPortalFeedback);
        document.addEventListener('livewire:navigated', showPortalFeedback);
    })();
</script>
