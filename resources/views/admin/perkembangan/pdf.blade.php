@php
    // View: resources/views/admin/perkembangan/pdf.blade.php
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 portrait; margin: 21px 29px; }

        body {
            margin: 0;
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            line-height: 1.35;
            color: #111827;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table {
            width: 62%;
            margin: 0 auto 0 76px;
            border-collapse: collapse;
        }

        .header-table td {
            border: 0;
            vertical-align: middle;
            padding: 0;
        }

        .header-logo-cell {
            width: 92px;
            padding-right: 0;
        }

        .header-logo {
            width: 118px;
            height: auto;
            display: block;
        }

        .header-text {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .header h2 {
            margin: 2px 0 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.2;
        }

        .line {
            border-top: 1px solid #111827;
            margin: 8px 0 12px;
        }

        .meta-wrap {
            margin-bottom: 10px;
            padding: 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 1px 4px;
            vertical-align: top;
            font-size: 13px;
            border: 0;
        }

        .meta-label {
            width: 16%;
            font-weight: bold;
            white-space: nowrap;
        }

        .meta-sep {
            width: 3%;
            text-align: center;
        }

        .meta-gap {
            width: 5%;
        }

        .section-block {
            width: 100%;
            margin-bottom: 9px;
        }

        .section-heading {
            padding: 6px 8px;
            font-size: 15px;
            font-weight: bold;
            page-break-after: avoid;
            break-after: avoid;
        }

        .section-body {
            padding: 8px 10px;
            page-break-before: avoid;
            break-before: avoid;
        }

        .entry {
            margin: 0 0 10px;
            page-break-inside: auto;
        }

        .entry + .entry {
            padding-top: 6px;
        }

        .entry:last-child {
            margin-bottom: 0;
        }

        .entry-title {
            margin: 0 0 5px;
            font-weight: bold;
            font-size: 14px;
            page-break-after: avoid;
        }

        .entry-label {
            margin: 0 0 2px;
            font-weight: bold;
            page-break-after: avoid;
        }

        .entry-text {
            margin: 0 0 5px 0;
            text-align: justify;
        }

        .notes-list {
            margin: 0;
            padding-left: 18px;
        }

        .notes-list li {
            margin: 0 0 6px;
            text-align: justify;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo.png');
        $cleanText = function ($value) {
            $value = trim((string) ($value ?? '-'));
            $value = str_replace(['ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¢', 'ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â¢'], '', $value);
            $value = preg_replace('/^\s*(?:[-*]|\d+[.)])\s*/', '', $value);
            return $value === '' ? '-' : trim($value);
        };

        $catatanList = collect(preg_split('/\r\n|\r|\n/', (string) ($perkembangan->catatan_pengembangan ?? '')))
            ->map(fn ($item) => $cleanText($item))
            ->reject(fn ($item) => $item === '-')
            ->filter()
            ->values();

        $detailGroups = $perkembangan->groupedDetailsByCategory();
    @endphp

    <div class="header">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="{{ $logoPath }}" alt="Logo TK" class="header-logo">
                </td>
                <td class="header-text">
                    <h1>LAPORAN PERKEMBANGAN SISWA</h1>
                    <h2>TK SWASTA YP. KARYA ANUGERAH</h2>
                    <h2>(WINFIELD)</h2>
                    <p>Jln. A H Nasution Komp Titi Kuning Mas Blok F1-4</p>
                    <p>Kec. Medan Johor Kel. Titi Kuning - Medan</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="line"></div>

    <div class="meta-wrap">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nama Anak</td>
                <td class="meta-sep">:</td>
                <td>{{ $perkembangan->siswa->nama }}</td>
                <td class="meta-gap"></td>
                <td class="meta-label">Kelas</td>
                <td class="meta-sep">:</td>
                <td>{{ $perkembangan->kelas->nama_kelas ?? ($perkembangan->guru->kelas->nama_kelas ?? '-') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Bulan / Tahun</td>
                <td class="meta-sep">:</td>
                <td>{{ $monthOptions[$perkembangan->bulan] ?? $perkembangan->bulan }} / {{ $perkembangan->tahun }}</td>
                <td class="meta-gap"></td>
                <td class="meta-label">Guru</td>
                <td class="meta-sep">:</td>
                <td>{{ $perkembangan->guru->nama }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tahun Ajaran</td>
                <td class="meta-sep">:</td>
                <td>{{ $perkembangan->siswa->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                <td class="meta-gap"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    @foreach($detailGroups as $kategori => $details)
        <div class="section-block">
            <div class="section-heading">{{ $kategori }}</div>
            <div class="section-body">
                @forelse($details as $detail)
                    @php
                        $halBerkembang = $cleanText($detail->hal_berkembang);
                        $perluDiperhatikan = $cleanText($detail->perlu_diperhatikan);
                    @endphp
                    @continue($halBerkembang === '-' && $perluDiperhatikan === '-')
                    <div class="entry">
                        <p class="entry-title">{{ $detail->nama_aspek }}</p>
                        @if($halBerkembang !== '-')
                            <p class="entry-label">Sudah berkembang</p>
                            <p class="entry-text">{{ $halBerkembang }}</p>
                        @endif
                        @if($perluDiperhatikan !== '-')
                            <p class="entry-label">Hal yang perlu diperhatikan</p>
                            <p class="entry-text">{{ $perluDiperhatikan }}</p>
                        @endif
                    </div>
                @empty
                    <p class="entry-text">-</p>
                @endforelse
            </div>
        </div>
    @endforeach

    <div class="section-block">
        <div class="section-heading">Catatan</div>
        <div class="section-body">
            @if($catatanList->isNotEmpty())
                @foreach($catatanList as $note)
                    <p class="entry-text">{{ $note }}</p>
                @endforeach
            @else
                <p class="entry-text">Tidak ada catatan tambahan.</p>
            @endif
        </div>
    </div>
</body>
</html>
