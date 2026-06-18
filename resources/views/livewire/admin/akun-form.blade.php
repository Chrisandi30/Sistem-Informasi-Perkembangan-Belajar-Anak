@php
    // View: resources/views/livewire/admin/akun-form.blade.php
@endphp
<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="form-label">Nama</label>
            <input type="text" wire:model.defer="name" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div>
            <label class="form-label">Username</label>
            <input type="text" wire:model.defer="username" class="form-control" required>
            @error('username') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        @if($showRoleField)
            <div>
                <label class="form-label">Role</label>
                <select wire:model.defer="role" class="form-select" required>
                    @foreach($roleOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        @else
            <div>
                <label class="form-label">Role</label>
                <input type="text" class="form-control" value="{{ $roleOptions[$role] ?? ucfirst(str_replace('_', ' ', $role)) }}" readonly>
                <input type="hidden" wire:model.defer="role">
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        @endif
        <div>
            <label class="form-label">Status</label>
            <select wire:model.defer="is_active" class="form-select" required @if($isOwnAccount) disabled @endif>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
            @if($isOwnAccount)
                <small class="text-muted d-block mt-1">Akun yang sedang digunakan harus tetap aktif.</small>
            @endif
            @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div>
            <label class="form-label">{{ $isEdit ? 'Password Baru' : 'Password' }}</label>
            <div class="relative">
                <input type="password" wire:model.defer="password" class="form-control pe-5" data-password-field @if(!$isEdit) required @endif>
                <button type="button" class="absolute right-3 top-1/2 z-[2] -translate-y-1/2 text-[#7a899b] transition hover:text-[#7f1d1d]" data-password-toggle aria-label="Tampilkan password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            @if($isEdit)
                <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah password.</small>
            @endif
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div>
            <label class="form-label">Konfirmasi Password</label>
            <div class="relative">
                <input type="password" wire:model.defer="password_confirmation" class="form-control pe-5" data-password-field @if(!$isEdit) required @endif>
                <button type="button" class="absolute right-3 top-1/2 z-[2] -translate-y-1/2 text-[#7a899b] transition hover:text-[#7f1d1d]" data-password-toggle aria-label="Tampilkan konfirmasi password">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
            <span wire:loading wire:target="save">Memproses...</span>
        </button>
        <a href="{{ route('admin.akun.index') }}" class="btn btn-cancel">Batal</a>
    </div>
</form>