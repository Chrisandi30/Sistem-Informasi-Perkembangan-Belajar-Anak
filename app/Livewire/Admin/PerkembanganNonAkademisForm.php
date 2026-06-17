<?php

namespace App\Livewire\Admin;

use App\Models\PerkembanganNonAkademis;
use Livewire\Component;

class PerkembanganNonAkademisForm extends Component
{
    public ?PerkembanganNonAkademis $perkembanganNonAkademis = null;

    public string $kategori_aspek = '';
    public string $nama_aspek = '';
    public string $urutan = '1';
    public bool $is_active = true;

    public function mount(?PerkembanganNonAkademis $perkembanganNonAkademis = null): void
    {
        $this->perkembanganNonAkademis = $perkembanganNonAkademis;

        if ($this->isEditing()) {
            $this->kategori_aspek = $perkembanganNonAkademis->kategori_aspek ?? '';
            $this->nama_aspek = $perkembanganNonAkademis->nama_aspek ?? '';
            $this->urutan = (string) ($perkembanganNonAkademis->urutan ?? 1);
            $this->is_active = (bool) $perkembanganNonAkademis->is_active;
        }
    }

    public function save()
    {
        $data = $this->validate([
            'kategori_aspek' => ['required', 'string', 'max:50'],
            'nama_aspek' => ['required', 'string', 'max:20'],
            'urutan' => ['required', 'integer', 'min:1', 'max:999'],
            'is_active' => ['required', 'boolean'],
        ]);

        $payload = [
            'kategori_aspek' => trim($data['kategori_aspek']),
            'nama_aspek' => trim($data['nama_aspek']),
            'urutan' => (int) $data['urutan'],
            'is_active' => (bool) $data['is_active'],
        ];

        if ($this->isEditing()) {
            $this->perkembanganNonAkademis->update($payload);
            session()->flash('success', 'Aspek perkembangan non akademis berhasil diubah.');
        } else {
            PerkembanganNonAkademis::create($payload);
            session()->flash('success', 'Aspek perkembangan non akademis berhasil ditambahkan.');
        }

        return $this->redirectRoute('admin.perkembangan-non-akademis.index', navigate: true);
    }

    private function isEditing(): bool
    {
        return $this->perkembanganNonAkademis instanceof PerkembanganNonAkademis && $this->perkembanganNonAkademis->exists;
    }

    public function render()
    {
        return view('livewire.admin.perkembangan-non-akademis-form', [
            'isEdit' => $this->isEditing(),
        ]);
    }
}
