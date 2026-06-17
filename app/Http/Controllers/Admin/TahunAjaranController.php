<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    public function index()
    {
        return view('admin.tahun_ajaran.index');
    }

    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    public function edit(TahunAjaran $tahun_ajaran)
    {
        return view('admin.tahun_ajaran.edit', compact('tahun_ajaran'));
    }
}