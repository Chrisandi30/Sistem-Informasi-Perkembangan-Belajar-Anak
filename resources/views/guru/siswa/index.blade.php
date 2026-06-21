@extends('layouts.app')
@section('title', 'Daftar Siswa Kelas ' . $guru->kelas->nama_kelas)
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Daftar Siswa Kelas {{ $guru->kelas->nama_kelas }}</h5>
</div>

{{-- Form untuk menerima dan mengirim data pengguna. --}}
<form method="get" action="{{ route('guru.siswa.index') }}" class="mb-3" id="guruSiswaFilterForm">
    <div class="responsive-search-field relative" style="max-width: 200px;">
        <span class="pointer-events-none absolute start-0 top-50 z-[2] translate-middle-y ms-3 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span>
        <input type="text" name="search" value="{{ $search }}" class="form-control" style="padding-left: 44px;" placeholder="Search" autocomplete="off">
    </div>
    <input type="hidden" name="per_page" value="{{ $perPage }}">
</form>

<div id="guruSiswaResults">
    @include('guru.siswa.partials.table')
</div>
@endsection

@push('page-scripts')
    @include('partials.scripts.app-feedback')
    <script>
        // Inisialisasi fungsi initGuruSiswaAjax pada halaman.
        function initGuruSiswaAjax() {
            const form = document.getElementById('guruSiswaFilterForm');
            const results = document.getElementById('guruSiswaResults');
            const searchInput = form?.querySelector('input[name="search"]');
            const perPageInput = form?.querySelector('input[name="per_page"]');

            if (!form || !results || form.dataset.ajaxBound === '1') {
                return;
            }

            form.dataset.ajaxBound = '1';
            let searchTimer = null;

            // Jalankan proses scrollToPageTop pada halaman.
            const scrollToPageTop = () => {
                if (document.activeElement instanceof HTMLElement) {
                    document.activeElement.blur();
                }

                window.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
                document.documentElement.scrollTo?.({ top: 0, left: 0, behavior: 'smooth' });
                document.body.scrollTo?.({ top: 0, left: 0, behavior: 'smooth' });

                document.querySelectorAll('.content-inner, main, section, .min-h-screen').forEach((container) => {
                    container.scrollTo?.({ top: 0, left: 0, behavior: 'smooth' });
                });
            };

            // Jalankan proses buildUrl pada halaman.
            const buildUrl = (url = form.action, keepPage = false) => {
                const nextUrl = new URL(url, window.location.origin);
                const search = searchInput?.value?.trim() || '';
                const perPage = perPageInput?.value || '5';

                if (!keepPage) {
                    nextUrl.searchParams.delete('page');
                }

                nextUrl.searchParams.set('per_page', perPage);

                if (search !== '') {
                    nextUrl.searchParams.set('search', search);
                } else {
                    nextUrl.searchParams.delete('search');
                }

                return nextUrl;
            };

            // Jalankan proses loadResults pada halaman.
            const loadResults = async (url, pushHistory = true) => {
                try {
                    window.tkWinfieldUi?.hideLoading?.();

                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat data siswa.');
                    }

                    const html = await response.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const nextResults = doc.getElementById('guruSiswaResults');

                    if (!nextResults) {
                        throw new Error('Konten siswa tidak ditemukan.');
                    }

                    results.innerHTML = nextResults.innerHTML;

                    if (pushHistory) {
                        window.history.pushState({}, '', url);
                    }

                    requestAnimationFrame(() => {
                        scrollToPageTop();
                        setTimeout(scrollToPageTop, 60);
                        setTimeout(scrollToPageTop, 180);
                    });
                } catch (error) {
                    window.location.href = url;
                } finally {
                    window.tkWinfieldUi?.hideLoading?.();
                }
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                loadResults(buildUrl().toString());
            });

            searchInput?.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => loadResults(buildUrl().toString()), 350);
            });

            document.addEventListener('change', function (event) {
                const select = event.target.closest('#guruSiswaResults select[name="per_page"]');
                if (!select) {
                    return;
                }

                if (perPageInput) {
                    perPageInput.value = select.value;
                }

                loadResults(buildUrl().toString());
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('#guruSiswaResults .pagination a');
                if (!link) {
                    return;
                }

                event.preventDefault();
                loadResults(buildUrl(link.href, true).toString());
            });

            window.addEventListener('popstate', function () {
                loadResults(window.location.href, false);
            });
        }

        document.addEventListener('DOMContentLoaded', initGuruSiswaAjax);
        document.addEventListener('livewire:navigated', initGuruSiswaAjax);
    </script>
@endpush
