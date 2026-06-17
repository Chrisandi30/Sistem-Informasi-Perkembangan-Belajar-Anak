<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerkembanganNonAkademis;

class PerkembanganNonAkademisController extends Controller
{
    public function index()
    {
        return view('admin.perkembangan_non_akademis.index');
    }

    public function create()
    {
        return view('admin.perkembangan_non_akademis.create');
    }

    public function edit(PerkembanganNonAkademis $perkembangan_non_akademi)
    {
        return view('admin.perkembangan_non_akademis.edit', [
            'perkembanganNonAkademis' => $perkembangan_non_akademi,
        ]);
    }
}
