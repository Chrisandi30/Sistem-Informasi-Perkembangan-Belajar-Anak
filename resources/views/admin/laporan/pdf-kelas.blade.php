<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 portrait; margin: 21px 29px; }
        body {
            font-size: 14px;
            font-family: "Times New Roman", Times, serif;
            line-height: 1.5;
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
            width: 120px;
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
            font-size: 14px;
            margin: 1px 0 0;
            font-weight: bold;
            line-height: 1.2;
        }
        .line {
            border-top: 1px solid #111827;
            margin: 8px 0 12px;
        }
        .kelas-title {
            margin: 0 0 10px;
            font-size: 17px;
            font-weight: bold;
        }
        .info-table {
            width: auto;
            border-collapse: collapse;
            margin: 0 0 12px;
        }
        .info-table td {
            border: 0;
            padding: 0 6px 2px 0;
            font-size: 15px;
            vertical-align: top;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
            white-space: nowrap;
        }
        .info-separator {
            width: 10px;
            font-weight: bold;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #222;
            padding: 6px;
            font-size: 15px;
            vertical-align: top;
        }
        th {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="{{ storage_path('app/public/images/logo.png') }}" alt="Logo Winfield" class="header-logo">
                </td>
                <td class="header-text">
                    <h1>LAPORAN KELAS</h1>
                    <h2>TK SWASTA YP. KARYA ANUGERAH</h2>
                    <h2>(WINFIELD)</h2>
                    <p>Jln. A H Nasution Komp Titi Kuning Mas Blok F1-4</p>
                    <p>Kec. Medan Johor Kel. Titi Kuning - Medan</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="line"></div>

    @foreach($kelas as $k)
        @php
            $tahunAjaranKelas = $k->siswas->first(fn ($siswa) => !is_null($siswa->tahun_ajaran_id))?->tahunAjaran
                ?? $k->siswas->first()?->tahunAjaran;
        @endphp
        <table class="info-table">
            <tr>
                <td class="info-label">Guru</td>
                <td class="info-separator">:</td>
                <td>{{ $k->gurus->pluck('nama')->join(', ') ?: '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Kelas</td>
                <td class="info-separator">:</td>
                <td>{{ $k->nama_kelas }}</td>
            </tr>
            <tr>
                <td class="info-label">Tahun Ajaran</td>
                <td class="info-separator">:</td>
                <td>{{ $tahunAjaranKelas->tahun_ajaran ?? '-' }}</td>
            </tr>
        </table>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:18%;">Nama Siswa</th>
                    <th style="width:16%;">NIS / NISN</th>
                    <th style="width:20%;">Tempat Tanggal Lahir</th>
                    <th style="width:12%;">Jenis Kelamin</th>
                    <th style="width:29%;">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($k->siswas as $siswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ ($siswa->nis ?: '-') . ' / ' . ($siswa->nisn ?: '-') }}</td>
                        <td>
                            <div>{{ $siswa->tempat_lahir ?: '-' }}</div>
                            @if($siswa->tanggal_lahir)
                                <div>{{ optional($siswa->tanggal_lahir)->format('d-m-Y') }}</div>
                            @endif
                        </td>
                        <td>{{ $siswa->jenis_kelamin_label }}</td>
                        <td>{{ $siswa->alamat ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Data siswa tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
</body>
</html>
