<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ReturnsToIndex;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GuruForm extends Component
{

    use ReturnsToIndex;
public ?Guru $guru = null;

    public string $nama = '';
    public string $nuptk = '';
    public string $jenis_kelamin = 'L';
    public string $alamat = '';
    public string $jenjang_pendidikan = '';
    public string $kelas_id = '';
    public string $username = '';
    public string $password = '';

    public function mount(?Guru $guru = null): void
    {

        $this->initializeReturnTo(route('admin.guru.index'));
$this->guru = $guru;

        if ($this->isEditing()) {
            $this->nama = $guru->nama ?? '';
            $this->nuptk = $guru->nuptk ?? '';
            $this->jenis_kelamin = $guru->jenis_kelamin ?? 'L';
            $this->alamat = $guru->alamat ?? '';
            $this->jenjang_pendidikan = $guru->jenjang_pendidikan ?? '';
            $this->kelas_id = (string) $guru->kelas_id;
            $this->username = $guru->user?->username ?? '';
            return;
        }
    }

    public function save()
    {
        $hadUserAccount = (bool) $this->guru?->user_id;

        $data = $this->validate([
            'nama' => ['required', 'string', 'max:40'],
            'nuptk' => ['nullable', 'string', 'max:25'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string'],
            'jenjang_pendidikan' => ['required', 'string', 'max:2', 'in:D4,S1,S2,S3'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($this->guru?->user_id)],
            'password' => [$this->requiresPassword() ? 'required' : 'nullable', 'string', 'min:8'],
        ]);

        if ($this->isEditing()) {
            DB::transaction(function () use ($data) {
                $this->guru->loadMissing('user');

                $this->guru->update([
                    'nama' => $data['nama'],
                    'nuptk' => $data['nuptk'] ?: null,
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'alamat' => $data['alamat'],
                    'jenjang_pendidikan' => $data['jenjang_pendidikan'],
                    'kelas_id' => (int) $data['kelas_id'],
                ]);

                if ($this->guru->user) {
                    $userData = [
                        'username' => $data['username'],
                        'name' => $data['nama'],
                    ];
                    if (! empty($data['password'])) {
                        $userData['password'] = $data['password'];
                    }
                    $this->guru->user->update($userData);
                } else {
                    $user = User::create([
                        'username' => $data['username'],
                        'name' => $data['nama'],
                        'role' => 'guru',
                        'password' => $data['password'],
                    ]);

                    $this->guru->update([
                        'user_id' => $user->id,
                    ]);
                }
            });

            session()->flash('success', $hadUserAccount
                ? 'Data guru berhasil diubah.'
                : 'Data guru berhasil diubah dan akun login baru berhasil dibuat.');
        } else {
            DB::transaction(function () use ($data) {
                $user = User::create([
                    'username' => $data['username'],
                    'name' => $data['nama'],
                    'role' => 'guru',
                    'password' => $data['password'],
                ]);

                Guru::create([
                    'nama' => $data['nama'],
                    'nuptk' => $data['nuptk'] ?: null,
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'alamat' => $data['alamat'],
                    'jenjang_pendidikan' => $data['jenjang_pendidikan'],
                    'kelas_id' => (int) $data['kelas_id'],
                    'user_id' => $user->id,
                ]);
            });

            session()->flash('success', 'Guru dan akun login berhasil ditambahkan.');
        }

        return $this->redirectToIndex();
    }

    private function requiresPassword(): bool
    {
        return ! $this->isEditing() || ! $this->guru?->user_id;
    }

    private function isEditing(): bool
    {
        return $this->guru instanceof Guru && $this->guru->exists;
    }

    public function render()
    {
        return view('livewire.admin.guru-form', [
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'isEdit' => $this->isEditing(),
        ]);
    }
}
