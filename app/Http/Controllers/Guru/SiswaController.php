<?php

// Controller: app/Http/Controllers/Guru/SiswaController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        abort_unless($guru, 403);

        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 5);
        $perPage = in_array($perPage, [5, 10, 25, 50], true) ? $perPage : 5;

        $siswas = Siswa::with('kelas')
            ->where('kelas_id', $guru->kelas_id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('guru.siswa.index', compact('siswas', 'guru', 'search', 'perPage'));
    }
}