@php
    // View: resources/views/livewire/admin/tahun-ajaran-form.blade.php
@endphp
{{-- Form untuk menerima dan mengirim data pengguna. --}}
<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="mb-3 md:mb-0">
            <label class="form-label">Tahun Ajaran</label>
            <input type="text" wire:model.defer="tahun_ajaran" class="form-control" required>
            @error('tahun_ajaran') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3 md:mb-0">
            <label class="form-label">Status</label>
            <select wire:model.defer="is_active" class="form-select" required>
                <option value="0">Tidak Aktif</option>
                <option value="1">Aktif</option>
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




