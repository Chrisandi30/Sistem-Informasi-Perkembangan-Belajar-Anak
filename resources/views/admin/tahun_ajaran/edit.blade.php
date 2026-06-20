@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')
@section('content')
<h5>Edit Tahun Ajaran</h5>
<livewire:admin.tahun-ajaran-form :tahun_ajaran="$tahun_ajaran" />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush
