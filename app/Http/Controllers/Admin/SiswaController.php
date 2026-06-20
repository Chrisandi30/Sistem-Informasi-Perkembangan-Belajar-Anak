<?php

// Controller: app/Http/Controllers/Admin/SiswaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Support\SafeReturnUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'user'])->latest()->paginate(10);
        return view('admin.siswa.index', compact('siswas'));
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    // Tampilkan detail data yang dipilih.
    public function show(Request $request, Siswa $siswa)
    {
        $siswa->load(['kelas', 'tahunAjaran', 'user']);
        $returnTo = SafeReturnUrl::fromRequest($request, route('admin.siswa.index'));

        return view('admin.siswa.show', compact('siswa', 'returnTo'));
    }

    // Validasi dan simpan data baru ke database.
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:40'],
            'nis' => ['nullable', 'string', 'max:11'],
            'nisn' => ['nullable', 'string', 'max:10'],
            'tempat_lahir' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nama_ayah' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ibu,nama_wali'],
            'nama_ibu' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ayah,nama_wali'],
            'nama_wali' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ayah,nama_ibu'],
            'alamat' => ['required', 'string'],
            'nomor_telepon' => ['nullable', 'string', 'max:13'],
            'pas_foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['nama'],
                'role' => 'orang_tua',
                'password' => $data['password'],
            ]);

            $pasFoto = $request->hasFile('pas_foto')
                ? $request->file('pas_foto')->store('pas-foto-siswa', 'public')
                : null;

            Siswa::create([
                'nama' => $data['nama'],
                'nis' => $data['nis'] ?? null,
                'nisn' => $data['nisn'] ?? null,
                'tempat_lahir' => $data['tempat_lahir'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'agama' => $data['agama'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_ayah' => $data['nama_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'nama_wali' => $data['nama_wali'] ?? null,
                'alamat' => $data['alamat'],
                'nomor_telepon' => $data['nomor_telepon'] ?? null,
                'pas_foto' => $pasFoto,
                'kelas_id' => $data['kelas_id'],
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa dan akun orang tua berhasil ditambahkan.');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    // Validasi dan simpan perubahan data.
    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:40'],
            'nis' => ['nullable', 'string', 'max:11'],
            'nisn' => ['nullable', 'string', 'max:10'],
            'tempat_lahir' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nama_ayah' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ibu,nama_wali'],
            'nama_ibu' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ayah,nama_wali'],
            'nama_wali' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ayah,nama_ibu'],
            'alamat' => ['required', 'string'],
            'nomor_telepon' => ['nullable', 'string', 'max:13'],
            'pas_foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $siswa->user_id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($data, $request, $siswa) {
            $pasFoto = $siswa->pas_foto;

            if ($request->hasFile('pas_foto')) {
                if ($pasFoto) {
                    Storage::disk('public')->delete($pasFoto);
                }
                $pasFoto = $request->file('pas_foto')->store('pas-foto-siswa', 'public');
            }

            $siswa->update([
                'nama' => $data['nama'],
                'nis' => $data['nis'] ?? null,
                'nisn' => $data['nisn'] ?? null,
                'tempat_lahir' => $data['tempat_lahir'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'agama' => $data['agama'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_ayah' => $data['nama_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'nama_wali' => $data['nama_wali'] ?? null,
                'alamat' => $data['alamat'],
                'nomor_telepon' => $data['nomor_telepon'] ?? null,
                'pas_foto' => $pasFoto,
                'kelas_id' => $data['kelas_id'],
            ]);

            $userData = [
                'username' => $data['username'],
                'name' => $data['nama'],
            ];
            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];
            }
            $siswa->user->update($userData);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diubah.');
    }

    // Hapus data yang dipilih dari database.
    public function destroy(Siswa $siswa)
    {
        if ($siswa->pas_foto) {
            Storage::disk('public')->delete($siswa->pas_foto);
        }

        $siswa->user()->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
