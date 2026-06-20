<?php
namespace App\Livewire\Admin;

use App\Livewire\Concerns\ReturnsToIndex;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;

class AkunForm extends Component
{

    use ReturnsToIndex;
public ?User $akun = null;

    public string $name = '';
    public string $username = '';
    public string $role = 'admin';
    public int $is_active = 1;
    public string $password = '';
    public string $password_confirmation = '';

    // Isi kondisi awal saat halaman atau komponen dibuka.
    public function mount(?User $akun = null): void
    {

        $this->initializeReturnTo(route('admin.akun.index'));
$this->akun = $akun;

        if ($this->isEditing()) {
            $this->name = $akun->name ?? '';
            $this->username = $akun->username ?? '';
            $this->role = $akun->role ?? 'admin';
            $this->is_active = (int) $akun->is_active;
        } else {
            $this->role = 'admin';
            $this->is_active = 1;
        }
    }

    // Validasi lalu simpan data dari formulir.
    public function save()
    {
        $data = $this->validate($this->rules());

        if ($this->isEditing()) {
            $role = $data['role'];
            if ($this->akun->id === auth()->id()) {
                $role = $this->akun->role;
            }

            $userData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'role' => $role,
                'is_active' => $this->akun->id === auth()->id() ? 1 : (int) $data['is_active'],
            ];
            if (! empty($data['password'])) {
                $userData['password'] = $data['password'];
            }
            $this->akun->update($userData);
            session()->flash('success', 'Akun berhasil diubah.');
        } else {
            User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'role' => $data['role'],
                'is_active' => (int) $data['is_active'],
                'password' => $data['password'],
            ]);
            session()->flash('success', 'Akun berhasil ditambahkan.');
        }

        return $this->redirectToIndex();
    }

    // Tentukan aturan validasi data formulir.
    private function rules(): array
    {
        $allowedRoles = $this->isEditing()
            ? array_keys($this->editableRoleOptions())
            : array_keys($this->creatableRoleOptions());

        return [
            'name' => ['required', 'string', 'max:40'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($this->akun?->id)],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'is_active' => ['required', 'in:0,1'],
            'password' => [$this->isEditing() ? 'nullable' : 'required', 'string', 'min:8', 'same:password_confirmation'],
            'password_confirmation' => [$this->isEditing() ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }

    // Periksa apakah formulir sedang dalam mode edit.
    private function isEditing(): bool
    {
        return $this->akun instanceof User && $this->akun->exists;
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        return view('livewire.admin.akun-form', [
            'isEdit' => $this->isEditing(),
            'showRoleField' => ! $this->isEditing(),
            'roleOptions' => $this->roleOptions(),
            'isOwnAccount' => $this->isEditing() && $this->akun?->id === auth()->id(),
        ]);
    }

    // Tangani proses role options.
    private function roleOptions(): array
    {
        return $this->isEditing()
            ? $this->editableRoleOptions()
            : $this->creatableRoleOptions();
    }

    // Tangani proses creatable role options.
    private function creatableRoleOptions(): array
    {
        return [
            'admin' => 'Admin',
            'kepala_sekolah' => 'Kepala Sekolah',
        ];
    }

    // Tangani proses editable role options.
    private function editableRoleOptions(): array
    {
        return match ($this->akun?->role) {
            'orang_tua' => ['orang_tua' => 'Orang Tua'],
            'guru' => ['guru' => 'Guru'],
            default => [
                'admin' => 'Admin',
                'kepala_sekolah' => 'Kepala Sekolah',
            ],
        };
    }
}
