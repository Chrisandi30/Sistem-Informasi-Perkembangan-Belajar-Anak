<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="mb-3 md:mb-0">
            <label class="form-label">Nama Mata Pelajaran</label>
            <input type="text" wire:model.defer="nama_mapel" class="form-control" required>
            @error('nama_mapel') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Kelas</label>
            <select wire:model.defer="kelas_id" class="form-select" required>
                <option value="">Pilih kelas</option>
                @foreach($kelas as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                @endforeach
            </select>
            @error('kelas_id') <small class="text-danger">{{ $message }}</small> @enderror
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
        <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-cancel">Batal</a>
    </div>
</form>




