<?php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use Livewire\Component;

class MataPelajaranForm extends Component
{
    public ?MataPelajaran $mataPelajaran = null;

    public string $nama_mapel = '';
    public string $kelas_id = '';
    public bool $is_active = true;

    public function mount(?MataPelajaran $mataPelajaran = null): void
    {
        $this->mataPelajaran = $mataPelajaran;
        if ($this->isEditing()) {
            $this->nama_mapel = $mataPelajaran->nama_mapel ?? '';
            $this->kelas_id = (string) $mataPelajaran->kelas_id;
            $this->is_active = (bool) $mataPelajaran->is_active;
        }
    }

    public function save()
    {
        $data = $this->validate([
            'nama_mapel' => ['required', 'string', 'max:40'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $payload = [
            'nama_mapel' => $data['nama_mapel'],
            'kelas_id' => (int) $data['kelas_id'],
            'is_active' => (bool) $data['is_active'],
        ];

        if ($this->isEditing()) {
            $this->mataPelajaran->update($payload);
            session()->flash('success', 'Mata pelajaran berhasil diubah.');
        } else {
            MataPelajaran::create($payload);
            session()->flash('success', 'Mata pelajaran berhasil ditambahkan.');
        }

        return $this->redirectRoute('admin.mata-pelajaran.index', navigate: true);
    }

    private function isEditing(): bool
    {
        return $this->mataPelajaran instanceof MataPelajaran && $this->mataPelajaran->exists;
    }

    public function render()
    {
        return view('livewire.admin.mata-pelajaran-form', [
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'isEdit' => $this->isEditing(),
        ]);
    }
}
