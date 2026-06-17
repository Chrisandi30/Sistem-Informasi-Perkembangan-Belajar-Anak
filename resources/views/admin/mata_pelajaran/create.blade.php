@extends('layouts.app')
@section('title', 'Tambah Mata Pelajaran')
@section('content')
<h5>Tambah Mata Pelajaran</h5>
<livewire:admin.mata-pelajaran-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


