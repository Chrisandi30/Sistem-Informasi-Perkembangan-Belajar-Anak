<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        return view('admin.tahun_ajaran.index');
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(TahunAjaran $tahun_ajaran)
    {
        return view('admin.tahun_ajaran.edit', compact('tahun_ajaran'));
    }
}
