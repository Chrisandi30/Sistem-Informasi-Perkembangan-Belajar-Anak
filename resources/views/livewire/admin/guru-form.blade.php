{{-- Form untuk menerima dan mengirim data pengguna. --}}
<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama</label><input type="text" wire:model.defer="nama" class="form-control" required>@error('nama') <small class="text-danger">{{ $message }}</small> @enderror</div>
        <div class="col-md-6"><label class="form-label">NUPTK</label><input type="text" wire:model.defer="nuptk" class="form-control">@error('nuptk') <small class="text-danger">{{ $message }}</small> @enderror</div>
        <div class="col-md-4"><label class="form-label">Jenis Kelamin</label><select wire:model.defer="jenis_kelamin" class="form-select"><option value="L">Laki Laki</option><option value="P">Perempuan</option></select>@error('jenis_kelamin') <small class="text-danger">{{ $message }}</small> @enderror</div>
        <div class="col-md-4">
            <label class="form-label">Jenjang Pendidikan</label>
            <select wire:model.defer="jenjang_pendidikan" class="form-select" required>
                <option value="">Pilih Jenjang Pendidikan</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
            @error('jenjang_pendidikan') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4"><label class="form-label">Kelas</label><select wire:model.defer="kelas_id" class="form-select" required><option value="">Pilih kelas</option>@foreach($kelas as $item)<option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>@endforeach</select>@error('kelas_id') <small class="text-danger">{{ $message }}</small> @enderror</div>
        <div class="col-md-8"><label class="form-label">Alamat</label><textarea wire:model.defer="alamat" class="form-control" rows="2" required></textarea>@error('alamat') <small class="text-danger">{{ $message }}</small> @enderror</div>
        @if($isEdit && $guru?->user_id)
            <div class="col-md-6">
                <label class="form-label">Username Akun</label>
                <input type="text" class="form-control pointer-events-none select-none bg-slate-100 text-slate-600" value="{{ $username }}" readonly tabindex="-1">
            </div>
            <div class="col-md-6">
                <label class="form-label">Password Akun</label>
                <input type="password" class="form-control pointer-events-none select-none bg-slate-100 text-slate-600" value="12345678" readonly tabindex="-1">
            </div>
        @else
            <div class="col-md-6">
                <label class="form-label">Username Akun</label>
                <input type="text" wire:model.defer="username" class="form-control" required>
                @if($isEdit)
                    <small class="text-muted d-block mt-1">Akun guru sudah tidak terhubung. Isi data akun baru untuk menyambungkannya kembali.</small>
                @endif
                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Password Akun</label>
                <div class="relative">
                    <input type="password" wire:model.defer="password" class="form-control pe-5" data-password-field @if(!$isEdit || !$guru?->user_id) required @endif>
                    <button type="button" class="absolute right-3 top-1/2 z-[2] -translate-y-1/2 text-[#7a899b] transition hover:text-[#7f1d1d]" data-password-toggle aria-label="Tampilkan password akun">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
                @if($isEdit)
                    <small class="text-muted d-block mt-1">Password wajib diisi untuk membuat akun login baru.</small>
                @endif
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        @endif
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
            <span wire:loading wire:target="save">Memproses...</span>
        </button>
        <a href="{{ $returnTo }}" class="btn btn-cancel">Batal</a>
    </div>
</form>
