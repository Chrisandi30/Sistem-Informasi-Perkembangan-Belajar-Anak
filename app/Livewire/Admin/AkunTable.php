<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AkunTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public int $perPage = 5;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function deleteAkun(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            session()->flash('error', 'Akun admin tidak dapat dihapus.');
            $this->dispatch('app-feedback', type: 'error', message: 'Akun admin tidak dapat dihapus.');
            return;
        }

        if ($id === auth()->id()) {
            session()->flash('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
            $this->dispatch('app-feedback', type: 'error', message: 'Akun yang sedang digunakan tidak dapat dihapus.');
            return;
        }

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        session()->flash('success', 'Akun berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Akun berhasil dihapus.');
    }

    public function render()
    {
        $search = trim($this->search);

        $users = User::with('siswa')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhereHas('siswa', function ($siswaQuery) use ($search) {
                            $siswaQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('nama_ayah', 'like', "%{$search}%")
                                ->orWhere('nama_ibu', 'like', "%{$search}%")
                                ->orWhere('nama_wali', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $users->getCollection()->transform(function (User $user) {
            if ($user->role === 'orang_tua' && $user->siswa) {
                $user->display_name = $user->siswa->nama;
                $user->secondary_name = $user->siswa->nama_orang_tua_label;
            } else {
                $user->display_name = $user->name;
                $user->secondary_name = null;
            }

            $user->is_own_account = $user->id === auth()->id();

            return $user;
        });

        return view('livewire.admin.akun-table', [
            'users' => $users,
            'hasSearch' => $search !== '',
        ]);
        
    }
}

