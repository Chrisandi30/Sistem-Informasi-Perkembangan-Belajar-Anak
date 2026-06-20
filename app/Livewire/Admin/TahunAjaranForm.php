<?php

// Livewire: app/Livewire/Admin/TahunAjaranForm.php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ReturnsToIndex;
use App\Models\TahunAjaran;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TahunAjaranForm extends Component
{

    use ReturnsToIndex;
public ?TahunAjaran $tahunAjaran = null;

    public string $tahun_ajaran = '';
    // Simpan nilai select sebagai string agar opsi edit selalu sesuai kondisi database.
    public string $is_active = '0';

    // Isi kondisi awal saat halaman atau komponen dibuka.
    public function mount(?TahunAjaran $tahun_ajaran = null): void
    {

        $this->initializeReturnTo(route('admin.tahun-ajaran.index'));
$this->tahunAjaran = $tahun_ajaran;

        if ($this->isEditing()) {
            $this->tahun_ajaran = $tahun_ajaran->tahun_ajaran ?? '';
            $this->is_active = $tahun_ajaran->is_active ? '1' : '0';
        }
    }

    // Validasi lalu simpan data dari formulir.
    public function save()
    {
        $data = $this->validate([
            'tahun_ajaran' => ['required', 'string', 'max:10', Rule::unique('tahun_ajaran', 'tahun_ajaran')->ignore($this->tahunAjaran?->id)],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        // Ubah nilai select menjadi boolean sebelum disimpan ke database.
        $data['is_active'] = $data['is_active'] === '1';

        if ($data['is_active']) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        if ($this->isEditing()) {
            $this->tahunAjaran->update($data);
            session()->flash('success', 'Data tahun ajaran berhasil diubah.');
        } else {
            TahunAjaran::create($data);
            session()->flash('success', 'Data tahun ajaran berhasil ditambahkan.');
        }

        return $this->redirectToIndex();
    }

    // Periksa apakah formulir sedang dalam mode edit.
    private function isEditing(): bool
    {
        return $this->tahunAjaran instanceof TahunAjaran && $this->tahunAjaran->exists;
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        return view('livewire.admin.tahun-ajaran-form', ['isEdit' => $this->isEditing()]);
    }
}
