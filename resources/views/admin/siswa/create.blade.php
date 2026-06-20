@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('content')
<h5>Tambah Siswa</h5>
<livewire:admin.siswa-form />
@endsection
@push('page-scripts')
    @include('partials.scripts.app-feedback')
@endpush


