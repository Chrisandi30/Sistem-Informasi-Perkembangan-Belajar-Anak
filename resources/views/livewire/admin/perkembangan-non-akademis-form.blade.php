@php
    // View: resources/views/livewire/admin/perkembangan-non-akademis-form.blade.php
@endphp
<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="mb-3 md:mb-0">
            <label class="form-label">Kategori Aspek</label>
            <input type="text" wire:model.defer="kategori_aspek" class="form-control" required>
            @error('kategori_aspek') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Nama Aspek</label>
            <input type="text" wire:model.defer="nama_aspek" class="form-control" required>
            @error('nama_aspek') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Urutan</label>
            <input type="number" min="1" wire:model.defer="urutan" class="form-control" required>
            @error('urutan') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Status</label>
            <select wire:model.defer="is_active" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
            @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
            <span wire:loading wire:target="save">Memproses...</span>
        </button>
        <a href="{{ $returnTo }}" class="btn btn-cancel">Batal</a>
    </div>
</form>
