@extends('layouts.app')
@section('title', 'Perkembangan Siswa')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 perkembangan-page-heading">
    <h5>Data Laporan Perkembangan</h5>
</div>

<livewire:admin.perkembangan-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
