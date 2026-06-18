@php
    // View: resources/views/admin/perkembangan/print.blade.php
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Perkembangan</title>
    <style>
        @page { size: A4 portrait; margin: 21px 29px; }

        :root {
            --page-bg: #eef2f7;
            --page-border: #d8dee8;
            --ink: #111827;
            --muted: #5b6678;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--page-bg);
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            color: var(--ink);
        }

        .print-shell {
            min-height: 100vh;
            padding: 28px 16px;
        }

        .print-page {
            width: 794px;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 21px 29px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
            border: 1px solid var(--page-border);
        }

        .toolbar {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 0 auto 18px;
        }

        .toolbar button,
        .toolbar a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 168px;
            border: 0;
            border-radius: 999px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            line-height: 1;
            cursor: pointer;
        }

        .toolbar .btn-print {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
        }

        .print-note {
            margin: 0 auto 18px;
            text-align: center;
            font-family: Inter, sans-serif;
            font-size: 13px;
            line-height: 1.55;
            color: var(--muted);
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
            margin: 1px 0 0;
            font-size: 14px;
            font-weight: bold;
            line-height: 1.2;
        }

        .line {
            border-top: 1px solid #111827;
            margin: 8px 0 12px;
        }

        .meta-wrap {
            margin-bottom: 14px;
            padding: 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 14px;
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
            margin-bottom: 12px;
        }

        .section-heading {
            padding: 8px 10px;
            font-size: 15px;
            font-weight: bold;
            page-break-after: avoid;
            break-after: avoid;
        }

        .section-body {
            padding: 10px 12px;
            page-break-before: avoid;
            break-before: avoid;
        }

        .entry {
            margin: 0 0 14px;
            page-break-inside: auto;
        }

        .entry + .entry {
            padding-top: 10px;
        }

        .entry:last-child {
            margin-bottom: 0;
        }

        .entry-title {
            margin: 0 0 8px;
            font-weight: bold;
            font-size: 15px;
            page-break-after: avoid;
        }

        .entry-label {
            margin: 0 0 3px;
            font-weight: bold;
            page-break-after: avoid;
        }

        .entry-text {
            margin: 0 0 8px 0;
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

        @media screen and (max-width: 700px) {
            .print-shell {
                padding: 20px 12px;
            }

            .toolbar button,
            .toolbar a {
                min-width: 0;
                width: min(240px, 100%);
            }

            .print-note {
                max-width: 340px;
                margin-bottom: 20px;
            }

            .print-page {
                width: 100%;
                max-width: 100%;
                padding: 16px 14px;
            }

            .header-table {
                width: 100%;
                margin: 0;
            }

            .header-logo-cell {
                width: 76px;
                padding-right: 8px;
            }

            .header-logo {
                width: 70px;
            }

            .header h1 {
                font-size: 15px;
            }

            .header h2 {
                font-size: 13px;
            }

            .header p {
                font-size: 10px;
            }

            .meta-table {
                table-layout: fixed;
            }

            .meta-table td {
                padding: 2px;
                font-size: 11px;
                overflow-wrap: anywhere;
            }

            .meta-label {
                width: 19%;
                white-space: normal;
            }

            .meta-sep {
                width: 4%;
            }

            .meta-gap {
                width: 2%;
            }

            .section-heading {
                padding: 7px 4px;
                font-size: 14px;
            }

            .section-body {
                padding: 8px 4px;
            }

            .entry-title {
                font-size: 14px;
            }

            .entry-text,
            .entry-label {
                font-size: 12px;
                line-height: 1.45;
            }
        }

        @media print {
            body {
                background: #fff;
            }

            .print-shell {
                padding: 0;
            }

            .toolbar,
            .print-note {
                display: none !important;
            }

            .print-page {
                width: auto;
                margin: 0;
                padding: 0;
                border: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    @php
        $logoPath = asset('images/logo.png');
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

    <div class="print-shell">
        <div class="toolbar">
            <button type="button" class="btn-print" onclick="window.print()">Cetak Sekarang</button>
        </div>
        <div class="print-note">Dialog cetak akan terbuka otomatis. Jika belum muncul, klik "Cetak Sekarang".</div>

        <div class="print-page">
            <div class="header">
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
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 250);
        });
    </script>
</body>
</html>
