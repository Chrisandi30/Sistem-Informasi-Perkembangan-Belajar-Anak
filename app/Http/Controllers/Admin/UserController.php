<?php

// Controller: app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

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

    public function edit(User $akun)
    {
        return view('admin.akun.edit', compact('akun'));
    }

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

    public function destroy(User $akun)
    {
        if ($akun->id === auth()->id()) {
            return back()->withErrors(['Akun yang sedang digunakan tidak dapat dihapus.']);
        }

        $akun->delete();
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
