@php
    // View: resources/views/livewire/admin/pengumuman-form.blade.php
@endphp
<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="mb-3 md:mb-0">
            <label class="form-label">Judul</label>
            <input type="text" wire:model.defer="judul" class="form-control" required>
            @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Tanggal Terbit</label>
            <input type="date" wire:model.defer="tanggal_terbit" class="form-control" required>
            @error('tanggal_terbit') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Tanggal Berakhir</label>
            <input type="date" wire:model.defer="tanggal_berakhir" class="form-control" required>
            @error('tanggal_berakhir') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Isi</label>
        <textarea wire:model.defer="isi" rows="4" class="form-control" required></textarea>
        @error('isi') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
            <span wire:loading wire:target="save">Memproses...</span>
        </button>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-cancel">Batal</a>
    </div>
</form>


