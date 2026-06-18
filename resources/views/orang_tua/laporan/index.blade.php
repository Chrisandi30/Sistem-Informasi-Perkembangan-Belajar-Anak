@extends('layouts.orang_tua')

@section('content')
    <div class="laporan-page-wrap portal-wide-page">
        <h1 class="mb-2 break-words text-[30px] font-extrabold text-[#1d2533] max-[700px]:text-[26px]">Perkembangan Anak</h1>

        <div class="portal-mobile-card mb-4 rounded-[28px] border border-[var(--portal-line)] bg-white shadow-[0_16px_36px_rgba(41,60,89,0.08)]">
            <div class="p-[26px] max-[700px]:p-4">
                <form method="get" class="row g-3 align-items-end" id="orangTuaLaporanFilterForm">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select name="bulan" class="form-select rounded-4">
                            <option value="">Pilih Bulan</option>
                            @foreach($monthOptions as $monthNumber => $monthName)
                                <option value="{{ $monthNumber }}" @selected((int) request('bulan') === $monthNumber)>{{ $monthName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tahun</label>
                        <select name="tahun" class="form-select rounded-4">
                            <option value="">Pilih Tahun</option>
                            @foreach($yearOptions as $year)
                                <option value="{{ $year }}" @selected((int) request('tahun') === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="orangTuaLaporanSubmitButton" style="background:#7f1d1d; border-color:#7f1d1d;">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="orangTuaLaporanResults">
            @if(! $shouldShowReports)
                <div class="rounded-[26px] border border-[var(--portal-line)] bg-white shadow-[0_16px_36px_rgba(41,60,89,0.08)]">
                    <div class="p-[26px] max-[700px]:p-4">
                        <div class="break-words rounded-[18px] border border-dashed border-[#c9d6e5] bg-[#f9fbfe] p-[22px] text-center text-[#5e7084]">Silakan pilih bulan dan tahun terlebih dahulu.</div>
                    </div>
                </div>
            @else
                @php
                    $cleanText = function ($value) {
                        $value = trim((string) ($value ?? '-'));
                        $value = str_replace([chr(226) . chr(128) . chr(162), 'ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¢'], '', $value);
                        $value = ltrim($value, "- \t\n\r\0\x0B");
                        return $value === '' ? '-' : trim($value);
                    };
                @endphp

                @forelse($perkembangans as $item)
                    @php
                        $detailGroups = $item->groupedDetailsByCategory();
                    @endphp
                    <div class="portal-mobile-card mb-4 rounded-[26px] border border-[var(--portal-line)] bg-white shadow-[0_16px_36px_rgba(41,60,89,0.08)]">
                        <div class="p-[26px] max-[700px]:p-4">
                            <div class="mb-[22px] flex items-start justify-between gap-4 max-[700px]:flex-col max-[700px]:items-stretch">
                                <div>
                                    <div class="mb-[6px] break-words text-[15px] font-extrabold text-[#1f2937]">Laporan {{ $monthOptions[$item->bulan] ?? $item->bulan }}/{{ $item->tahun }}</div>
                                    <div class="break-words text-[14px] leading-[1.6] text-[#6f86a6]">Guru pengisi: {{ $item->guru?->nama ?? '-' }}</div>
                                </div>
                                <div class="mb-0 inline-flex max-w-full items-center gap-2 whitespace-normal rounded-full bg-[#eef4ff] px-[14px] py-[9px] font-bold text-[#2f5f98]">
                                    <i class="fa-solid fa-book-open"></i>
                                    {{ $siswa->kelas?->nama_kelas ?? '-' }}
                                </div>
                            </div>

                            @foreach($detailGroups as $kategori => $details)
                                <div class="mb-4 rounded-[24px] border border-[#d8e5f4] bg-[linear-gradient(180deg,_#ffffff_0%,_#fbfdff_100%)] px-[22px] pt-[22px] pb-[10px] max-[700px]:rounded-[18px] max-[700px]:px-3 max-[700px]:pt-[14px] max-[700px]:pb-1">
                                    <div class="mb-[18px] break-words text-[14px] font-extrabold uppercase tracking-[0.04em] text-[#6f86a6]">{{ $kategori }}</div>
                                    @foreach($details as $detail)
                                        @php
                                            $halBerkembang = $cleanText($detail->hal_berkembang);
                                            $perluDiperhatikan = $cleanText($detail->perlu_diperhatikan);
                                        @endphp
                                        @continue($halBerkembang === '-' && $perluDiperhatikan === '-')
                                        <div class="mb-3 border-b border-[#e7eef8] pb-3 last:mb-0 last:border-b-0 last:pb-[4px]">
                                            <div class="mb-3 break-words text-[18px] font-extrabold leading-[1.35] text-[#1f2937] max-[700px]:text-[17px]">{{ $detail->nama_aspek }}</div>
                                            @if($halBerkembang !== '-')
                                                <span class="mb-[6px] block break-words text-[15px] font-extrabold text-[#1f2937]">Sudah berkembang</span>
                                                <span class="mb-4 block break-words text-justify leading-[1.85] text-[#1f2937] last:mb-0 max-[700px]:leading-[1.72]">{{ $halBerkembang }}</span>
                                            @endif
                                            @if($perluDiperhatikan !== '-')
                                                <span class="mb-[6px] block break-words text-[15px] font-extrabold text-[#1f2937]">Perlu diperhatikan</span>
                                                <span class="mb-4 block break-words text-justify leading-[1.85] text-[#1f2937] last:mb-0 max-[700px]:leading-[1.72]">{{ $perluDiperhatikan }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="mb-4 rounded-[24px] border border-[#d8e5f4] bg-[linear-gradient(180deg,_#ffffff_0%,_#fbfdff_100%)] px-[22px] pt-[22px] pb-[10px] max-[700px]:rounded-[18px] max-[700px]:px-3 max-[700px]:pt-[14px] max-[700px]:pb-1">
                                <div class="mb-[18px] break-words text-[14px] font-extrabold uppercase tracking-[0.04em] text-[#6f86a6]">Catatan Guru</div>
                                @php
                                    $catatanText = trim((string) ($item->catatan_pengembangan ?? ''));
                                    $catatanText = preg_replace('/Hal-hal yang perlu diperhatikan\s*\/\s*dikembangkan\s*:\s*/iu', '', $catatanText);
                                    $catatanText = str_replace(["\r\n", "\r", "\n"], ' ', $catatanText);
                                    $catatanText = preg_replace('/\s+/', ' ', $catatanText);

                                    $catatanLines = collect(preg_split('/(?=\d+\.)/u', $catatanText))
                                        ->map(fn ($line) => trim($line))
                                        ->filter(fn ($line) => $line !== '');

                                    if ($catatanLines->isEmpty()) {
                                        $catatanLines = collect([$catatanText])->filter(fn ($line) => trim($line) !== '');
                                    }
                                @endphp
                                <div class="mb-0 border-0 pb-0">
                                    @if($catatanLines->isNotEmpty())
                                        @foreach($catatanLines as $line)
                                            <div class="mb-2 block break-words text-justify leading-[1.85] text-[#1f2937] last:mb-0 max-[700px]:leading-[1.72]">{{ $line }}</div>
                                        @endforeach
                                    @else
                                        <div class="mb-0 block break-words text-justify leading-[1.85] text-[#1f2937] max-[700px]:leading-[1.72]">-</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[26px] border border-[var(--portal-line)] bg-white shadow-[0_16px_36px_rgba(41,60,89,0.08)]">
                        <div class="p-[26px] max-[700px]:p-4">
                            <div class="break-words rounded-[18px] border border-dashed border-[#c9d6e5] bg-[#f9fbfe] p-[22px] text-center text-[#5e7084]">Belum ada laporan perkembangan yang ditemukan untuk periode yang dipilih.</div>
                        </div>
                    </div>
                @endforelse

                <div class="portal-pagination">
                    {{ $perkembangans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function initOrangTuaLaporanAjax() {
            const form = document.getElementById('orangTuaLaporanFilterForm');
            const results = document.getElementById('orangTuaLaporanResults');
            const submitButton = document.getElementById('orangTuaLaporanSubmitButton');

            if (!form || !results || form.dataset.ajaxBound === 'true') {
                return;
            }

            form.dataset.ajaxBound = 'true';

            const setLoadingState = (isLoading) => {
                if (!submitButton) {
                    return;
                }

                submitButton.disabled = isLoading;
                submitButton.textContent = isLoading ? 'Memuat...' : 'Tampilkan';
            };

            const loadResults = async (url, pushHistory = true) => {
                setLoadingState(true);

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat laporan.');
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const nextResults = doc.getElementById('orangTuaLaporanResults');

                    if (!nextResults) {
                        throw new Error('Konten laporan tidak ditemukan.');
                    }

                    results.innerHTML = nextResults.innerHTML;

                    if (pushHistory) {
                        window.history.pushState({}, '', url);
                    }

                    results.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } catch (error) {
                    window.location.href = url;
                } finally {
                    setLoadingState(false);
                }
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const url = new URL(form.action || window.location.href);
                const formData = new FormData(form);

                url.search = '';

                for (const [key, value] of formData.entries()) {
                    if (String(value).trim() !== '') {
                        url.searchParams.set(key, value);
                    }
                }

                loadResults(url.toString());
            });

            document.addEventListener('click', function (event) {
                const link = event.target.closest('#orangTuaLaporanResults .pagination a');

                if (!link) {
                    return;
                }

                event.preventDefault();
                loadResults(link.href);
            });

            window.addEventListener('popstate', function () {
                loadResults(window.location.href, false);
            });
        }

        document.addEventListener('DOMContentLoaded', initOrangTuaLaporanAjax);
        document.addEventListener('livewire:navigated', initOrangTuaLaporanAjax);
    </script>
@endpush
