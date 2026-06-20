@extends('layouts.app')
@section('title', 'Data Laporan Perkembangan')
@section('content')
<div class="d-flex justify-content-between mb-3 perkembangan-page-heading">
    <h5>Data Laporan Perkembangan</h5>
</div>
<livewire:guru.perkembangan-table />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
