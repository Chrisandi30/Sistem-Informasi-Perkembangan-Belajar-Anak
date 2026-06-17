<?php

namespace App\Livewire\Admin;

use App\Models\Pengumuman;
use Livewire\Component;

class PengumumanForm extends Component
{
    public ?Pengumuman $pengumuman = null;

    public string $judul = '';
    public string $tanggal_terbit = '';
    public string $tanggal_berakhir = '';
    public string $isi = '';

    public function mount(?Pengumuman $pengumuman = null): void
    {
        $this->pengumuman = $pengumuman;
        if ($this->isEditing()) {
            $this->judul = $pengumuman->judul ?? '';
            $this->tanggal_terbit = $pengumuman->tanggal_terbit?->format('Y-m-d') ?? '';
            $this->tanggal_berakhir = $pengumuman->tanggal_berakhir?->format('Y-m-d') ?? '';
            $this->isi = $pengumuman->isi ?? '';
        }
    }

    public function save()
    {
        $data = $this->validate([
            'judul' => ['required', 'string', 'max:30'],
            'tanggal_terbit' => ['required', 'date'],
            'tanggal_berakhir' => ['required', 'date', 'after_or_equal:tanggal_terbit'],
            'isi' => ['required', 'string'],
        ]);

        if ($this->isEditing()) {
            $this->pengumuman->update($data);
            session()->flash('success', 'Pengumuman berhasil diubah.');
        } else {
            Pengumuman::create($data);
            session()->flash('success', 'Pengumuman berhasil ditambahkan.');
        }

        return $this->redirectRoute('admin.pengumuman.index', navigate: true);
    }

    private function isEditing(): bool
    {
        return $this->pengumuman instanceof Pengumuman && $this->pengumuman->exists;
    }

    public function render()
    {
        return view('livewire.admin.pengumuman-form', ['isEdit' => $this->isEditing()]);
    }
}
