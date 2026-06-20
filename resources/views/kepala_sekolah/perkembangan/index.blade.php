@extends('layouts.app')
@section('title', 'Review & Persetujuan')

@section('content')
    <div class="pt-3 review-page">
        <h5 class="mb-4">Review & Persetujuan Laporan</h5>
        <livewire:kepala-sekolah.review-table />
    </div>
@endsection
