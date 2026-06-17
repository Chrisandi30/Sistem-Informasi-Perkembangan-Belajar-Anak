<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="mb-3">
        <label class="form-label">Nama Kelas</label>
        <input type="text" wire:model.defer="nama_kelas" class="form-control" required>
        @error('nama_kelas') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
            <span wire:loading wire:target="save">Memproses...</span>
        </button>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-cancel">Batal</a>
    </div>
</form>





