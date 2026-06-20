<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $gurus = Guru::with(['kelas', 'user'])->latest()->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.guru.create', compact('kelas'));
    }

    // Validasi dan simpan data baru ke database.
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:40'],
            'nuptk' => ['nullable', 'string', 'max:25'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string'],
            'jenjang_pendidikan' => ['required', 'string', 'max:2', 'in:D4,S1,S2,S3'],
            'nomor_telepon' => ['nullable', 'string', 'max:13'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['nama'],
                'role' => 'guru',
                'password' => $data['password'],
            ]);

            Guru::create([
                'nama' => $data['nama'],
                'nuptk' => $data['nuptk'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'jenjang_pendidikan' => $data['jenjang_pendidikan'],
                'nomor_telepon' => $data['nomor_telepon'] ?? null,
                'kelas_id' => $data['kelas_id'],
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru dan akun login berhasil ditambahkan.');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(Guru $guru)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.guru.edit', compact('guru', 'kelas'));
    }

    // Validasi dan simpan perubahan data.
    public function update(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:40'],
            'nuptk' => ['nullable', 'string', 'max:25'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string'],
            'jenjang_pendidikan' => ['required', 'string', 'max:2', 'in:D4,S1,S2,S3'],
            'nomor_telepon' => ['nullable', 'string', 'max:13'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $guru->user_id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($data, $guru) {
            $guru->update([
                'nama' => $data['nama'],
                'nuptk' => $data['nuptk'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'jenjang_pendidikan' => $data['jenjang_pendidikan'],
                'nomor_telepon' => $data['nomor_telepon'] ?? null,
                'kelas_id' => $data['kelas_id'],
            ]);

            $userData = [
                'username' => $data['username'],
                'name' => $data['nama'],
            ];
            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];
            }
            $guru->user->update($userData);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diubah.');
    }

    // Hapus data yang dipilih dari database.
    public function destroy(Guru $guru)
    {
        $guru->user()->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
