<?php

namespace App\Livewire\Admin;

use App\Models\TahunAjaran;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TahunAjaranForm extends Component
{
    public ?TahunAjaran $tahunAjaran = null;

    public string $tahun_ajaran = '';
    public bool $is_active = false;

    public function mount(?TahunAjaran $tahun_ajaran = null): void
    {
        $this->tahunAjaran = $tahun_ajaran;

        if ($this->isEditing()) {
            $this->tahun_ajaran = $tahun_ajaran->tahun_ajaran ?? '';
            $this->is_active = (bool) $tahun_ajaran->is_active;
        }
    }

    public function save()
    {
        $data = $this->validate([
            'tahun_ajaran' => ['required', 'string', 'max:10', Rule::unique('tahun_ajaran', 'tahun_ajaran')->ignore($this->tahunAjaran?->id)],
            'is_active' => ['required', 'boolean'],
        ]);

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

        return $this->redirectRoute('admin.tahun-ajaran.index', navigate: true);
    }

    private function isEditing(): bool
    {
        return $this->tahunAjaran instanceof TahunAjaran && $this->tahunAjaran->exists;
    }

    public function render()
    {
        return view('livewire.admin.tahun-ajaran-form', ['isEdit' => $this->isEditing()]);
    }
}
