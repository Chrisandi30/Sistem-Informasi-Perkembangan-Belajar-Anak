@extends('layouts.orang_tua')

@section('content')
    <div class="portal-wide-page">
    <div class="mb-7">
        <h1 class="m-0 break-words text-[34px] font-extrabold leading-tight text-[#1f2937] max-[700px]:text-[27px]">Pengumuman</h1>
    </div>

    <div class="space-y-4">
        @forelse($pengumuman as $item)
            <article class="portal-mobile-card rounded-[22px] border border-[var(--portal-line)] bg-white p-[24px] shadow-[0_14px_32px_rgba(41,60,89,0.07)] max-[700px]:p-4">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                    <h2 class="m-0 break-words text-[22px] font-extrabold leading-tight text-[#1f2937] max-[700px]:text-[19px]">{{ $item->judul }}</h2>
                    <div class="inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-[#f2ecff] px-3 py-2 text-[13px] font-bold text-[#7f1d1d]">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $item->tanggal_terbit->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="text-[15px] leading-[1.8] text-[#1f2937]" style="white-space: pre-line; overflow-wrap:anywhere; text-align: justify;">{{ trim($item->isi) }}</div>
            </article>
        @empty
            <div class="portal-mobile-card rounded-[22px] border border-[var(--portal-line)] bg-white p-[24px] text-center text-[#5e7084] shadow-[0_14px_32px_rgba(41,60,89,0.07)]">
                Belum ada pengumuman sekolah yang tersedia saat ini.
            </div>
        @endforelse
    </div>

    <div class="portal-pagination">
        {{ $pengumuman->links() }}
    </div>
    </div>
@endsection
