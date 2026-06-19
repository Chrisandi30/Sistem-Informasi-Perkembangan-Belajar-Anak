<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ReturnsToIndex;
use App\Models\Kelas;
use Livewire\Component;
use Illuminate\Validation\Rule;

class KelasForm extends Component
{

    use ReturnsToIndex;
public ?Kelas $kelas = null;

    public string $nama_kelas = '';

    public function mount(?Kelas $kelas = null): void
    {

        $this->initializeReturnTo(route('admin.kelas.index'));
$this->kelas = $kelas;
        if ($this->isEditing()) {
            $this->nama_kelas = $kelas->nama_kelas ?? '';
        }
    }

    public function save()
    {
        $data = $this->validate([
            'nama_kelas' => ['required', 'string', 'max:20', Rule::unique('kelas', 'nama_kelas')->ignore($this->kelas?->id)],
        ]);

        if ($this->isEditing()) {
            $this->kelas->update($data);
            session()->flash('success', 'Data kelas berhasil diubah.');
        } else {
            Kelas::create($data);
            session()->flash('success', 'Data kelas berhasil ditambahkan.');
        }

        return $this->redirectToIndex();
    }

    private function isEditing(): bool
    {
        return $this->kelas instanceof Kelas && $this->kelas->exists;
    }

    public function render()
    {
        return view('livewire.admin.kelas-form', ['isEdit' => $this->isEditing()]);
    }
}
