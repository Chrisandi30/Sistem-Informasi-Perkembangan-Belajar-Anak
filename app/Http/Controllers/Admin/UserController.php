<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.akun.index', compact('users'));
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        return view('admin.akun.create');
    }

    // Validasi dan simpan data baru ke database.
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'role' => ['required', 'in:admin,kepala_sekolah'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create($data);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(User $akun)
    {
        return view('admin.akun.edit', compact('akun'));
    }

    // Validasi dan simpan perubahan data.
    public function update(Request $request, User $akun)
    {
        $allowedRoles = $akun->role === 'orang_tua'
            ? ['admin', 'guru', 'kepala_sekolah', 'orang_tua']
            : ['admin', 'guru', 'kepala_sekolah'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $akun->id],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $userData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'role' => $akun->id === auth()->id() ? $akun->role : $data['role'],
        ];
        if (!empty($data['password'])) {
            $userData['password'] = $data['password'];
        }

        $akun->update($userData);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diubah.');
    }

    // Hapus data yang dipilih dari database.
    public function destroy(User $akun)
    {
        if ($akun->id === auth()->id()) {
            return back()->withErrors(['Akun yang sedang digunakan tidak dapat dihapus.']);
        }

        $akun->delete();
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
