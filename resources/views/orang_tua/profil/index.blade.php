@extends('layouts.orang_tua')

@section('content')
    <div class="portal-wide-page">
    <h1 class="mb-2 break-words text-[30px] font-extrabold text-[#1f2937] max-[700px]:text-[26px]">Profil Siswa</h1>

    @if(!$siswa)
        <div class="portal-mobile-card rounded-[24px] border border-[var(--portal-line)] bg-white shadow-[0_16px_36px_rgba(41,60,89,0.08)]">
            <div class="flex h-full flex-col p-[20px] max-[700px]:p-4">
                <div class="break-words rounded-[18px] border border-dashed border-[#c9d6e5] bg-[#f9fbfe] p-[22px] text-center text-[#5e7084]">
                    Akun orang tua ini belum terhubung ke data siswa. Silakan hubungi admin sekolah untuk menghubungkan akun Anda.
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-[minmax(320px,.9fr)_minmax(0,1.8fr)] items-stretch gap-[22px] max-[1100px]:grid-cols-1">
            <div>
                <div class="portal-mobile-card rounded-[24px] border border-[var(--portal-line)] bg-white px-6 py-7 text-center shadow-[0_16px_36px_rgba(41,60,89,0.08)] max-[700px]:px-4 max-[700px]:py-5">
                    <div class="mx-auto mb-4 flex h-[172px] w-[140px] items-center justify-center overflow-hidden rounded-[26px] border-4 border-white bg-[linear-gradient(135deg,_#4b74a1,_#2f4b67)] text-[46px] font-extrabold text-white shadow-[0_14px_30px_rgba(47,75,103,0.24)] max-[700px]:h-[150px] max-[700px]:w-[120px]">
                        @if($siswa->pas_foto_url)
                            <img src="{{ $siswa->pas_foto_url }}" alt="Pas foto {{ $siswa->nama }}" class="block h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                        @endif
                    </div>
                    <div class="mb-1 break-words text-2xl font-extrabold text-[#1f2937]">{{ $siswa->nama }}</div>
                    <div class="mb-[18px] inline-flex max-w-full items-center gap-2 whitespace-normal rounded-full bg-[#eef4ff] px-[14px] py-[9px] font-bold text-[#2f5f98]">
                        <i class="fa-solid fa-school"></i>
                        {{ $siswa->kelas?->nama_kelas ?? 'Belum ada kelas' }}
                    </div>

                    <ul class="portal-profile-summary m-0 list-none p-0 text-start">
                        <li class="grid grid-cols-[128px_minmax(0,1fr)] gap-4 border-b border-[#edf2f7] py-3 text-[#506277]"><span class="whitespace-nowrap">Nama</span><strong class="block min-w-0 break-words text-right text-[#1f2937]">{{ $siswa->nama ?: '-' }}</strong></li>
                        <li class="grid grid-cols-[128px_minmax(0,1fr)] gap-4 border-b border-[#edf2f7] py-3 text-[#506277]"><span class="whitespace-nowrap">NISN</span><strong class="block min-w-0 break-words text-right text-[#1f2937]">{{ $siswa->nisn ?: '-' }}</strong></li>
                        <li class="grid grid-cols-[128px_minmax(0,1fr)] gap-4 border-b border-[#edf2f7] py-3 text-[#506277]"><span class="whitespace-nowrap">Tahun Ajaran</span><strong class="block min-w-0 break-words text-right text-[#1f2937]">{{ $siswa->tahunAjaran?->tahun_ajaran ?: '-' }}</strong></li>
                        <li class="grid grid-cols-[128px_minmax(0,1fr)] gap-4 py-3 text-[#506277]"><span class="whitespace-nowrap">Jenis Kelamin</span><strong class="block min-w-0 break-words text-right text-[#1f2937]">{{ $siswa->jenis_kelamin_label ?: '-' }}</strong></li>
                    </ul>
                </div>
            </div>

            <div class="h-full">
                <div class="portal-mobile-card h-full rounded-[24px] border border-[var(--portal-line)] bg-white shadow-[0_16px_36px_rgba(41,60,89,0.08)]">
                    <div class="flex h-full flex-col p-[20px] max-[700px]:p-4">
                        <h2 class="mb-[9px] break-words text-[18px] font-extrabold text-[#1f2937]">Data Lengkap Siswa</h2>
                        <div class="grid flex-1 grid-cols-2 auto-rows-fr gap-2 max-[700px]:grid-cols-1">
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">NIS</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->nis ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">NISN</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->nisn ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Tempat, Tanggal Lahir</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ trim(($siswa->tempat_lahir ?: '-') . ', ' . ($siswa->tanggal_lahir?->translatedFormat('d F Y') ?: '-')) }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Jenis Kelamin</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->jenis_kelamin_label ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Agama</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->agama ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Kelas</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->kelas?->nama_kelas ?? '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Nama Ayah</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->nama_ayah ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Nama Ibu</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->nama_ibu ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Nama Wali</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->nama_wali ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Alamat</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->alamat ?: '-' }}</span></div>
                            <div class="flex min-w-0 flex-col justify-center rounded-[16px] border border-[var(--portal-line)] bg-[#fbfdff] px-3.5 py-2"><strong class="mb-1 block break-words text-[11px] font-extrabold uppercase tracking-[0.06em] text-[#7a899b]">Nomor Telepon Orang Tua/Wali</strong><span class="block break-words text-[14px] font-semibold leading-[1.35] text-[#1f2937]">{{ $siswa->nomor_telepon ?: '-' }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
@endsection
