@extends('layouts.app')

@section('content')
    <div class="pt-3 review-page">
        <h5 class="mb-4">
            <span class="page-title-desktop">Review & Persetujuan Laporan</span>
            <span class="page-title-mobile">Review & Persetujuan Laporan</span>
        </h5>
        <livewire:kepala-sekolah.review-table />
    </div>
@endsection
