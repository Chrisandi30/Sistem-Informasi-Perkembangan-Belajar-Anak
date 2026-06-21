@php
    // Form tambah dan edit siswa menggunakan komponen Livewire yang sama.
@endphp
{{-- Form untuk menerima dan mengirim data pengguna. --}}
<form wire:submit="save" class="card card-body form-shell bg-white p-7" enctype="multipart/form-data">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nama</label>
            <input type="text" wire:model.defer="nama" class="form-control" required>
            @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">NIS</label>
            <input type="text" wire:model.defer="nis" class="form-control">
            @error('nis') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">NISN</label>
            <input type="text" wire:model.defer="nisn" class="form-control">
            @error('nisn') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" wire:model.defer="tempat_lahir" class="form-control">
            @error('tempat_lahir') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tanggal Lahir</label>
            <div class="mobile-date-field">
                <input type="date" wire:model.defer="tanggal_lahir" class="form-control">
                <i class="fas fa-calendar-days mobile-date-icon" aria-hidden="true"></i>
            </div>
            @error('tanggal_lahir') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Jenis Kelamin</label>
            <select wire:model.defer="jenis_kelamin" class="form-select">
                <option value="L">Laki Laki</option>
                <option value="P">Perempuan</option>
            </select>
            @error('jenis_kelamin') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Agama</label>
            <input type="text" wire:model.defer="agama" class="form-control">
            @error('agama') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Kelas</label>
            <select wire:model.defer="kelas_id" class="form-select" required>
                <option value="">Pilih kelas</option>
                @foreach($kelas as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                @endforeach
            </select>
            @error('kelas_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tahun Ajaran</label>
            <select wire:model.defer="tahun_ajaran_id" class="form-select" required>
                <option value="">Pilih tahun ajaran</option>
                @foreach($tahunAjaran as $item)
                    <option value="{{ $item->id }}">{{ $item->tahun_ajaran }}{{ $item->is_active ? ' (Aktif)' : '' }}</option>
                @endforeach
            </select>
            @error('tahun_ajaran_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Ayah</label>
            <input type="text" wire:model.defer="nama_ayah" class="form-control">
            @error('nama_ayah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Ibu</label>
            <input type="text" wire:model.defer="nama_ibu" class="form-control">
            @error('nama_ibu') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Nama Wali</label>
            <input type="text" wire:model.defer="nama_wali" class="form-control">
            @error('nama_wali') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Nomor Telepon Orang Tua/Wali</label>
            <input type="text" wire:model.defer="nomor_kontak" class="form-control">
            @error('nomor_kontak') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Alamat</label>
            <textarea wire:model.defer="alamat" class="form-control" rows="2" required></textarea>
            @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        @if($isEdit)
            @if($siswa?->user_id)
                <div class="col-md-4">
                    <label class="form-label">Username Akun</label>
                    <input type="text" class="form-control pointer-events-none select-none bg-slate-100 text-slate-600" value="{{ $username }}" readonly tabindex="-1">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password Akun</label>
                    <input type="password" class="form-control pointer-events-none select-none bg-slate-100 text-slate-600" value="12345678" readonly tabindex="-1">
                </div>
            @else
                <div class="col-md-4">
                    <label class="form-label">Username Akun</label>
                    <input type="text" wire:model.defer="username" class="form-control" required>
                    <small class="text-muted d-block mt-1">Akun orang tua sudah tidak terhubung. Isi data akun baru untuk menyambungkannya kembali.</small>
                    @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password Akun</label>
                    <div class="relative">
                        <input type="password" wire:model.defer="password" class="form-control pe-5" data-password-field required>
                        <button type="button" class="absolute right-3 top-1/2 z-[2] -translate-y-1/2 text-[#7a899b] transition hover:text-[#7f1d1d]" data-password-toggle aria-label="Tampilkan password akun">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted d-block mt-1">Password wajib diisi untuk membuat akun login baru.</small>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            @endif
        @else
            <div class="col-md-4">
                <label class="form-label">Username Akun</label>
                <input type="text" wire:model.defer="username" class="form-control" required>
                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Password Akun</label>
                <div class="relative">
                        <input type="password" wire:model.defer="password" class="form-control pe-5" data-password-field required>
                        <button type="button" class="absolute right-3 top-1/2 z-[2] -translate-y-1/2 text-[#7a899b] transition hover:text-[#7f1d1d]" data-password-toggle aria-label="Tampilkan password akun">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        @endif
        <div class="col-md-4">
            <label class="form-label">Pas Foto Siswa</label>
            <input
                type="file"
                wire:model="pas_foto"
                class="form-control"
                accept="image/jpeg,image/png,.jpg,.jpeg,.png"
                onchange="
                    if (!this.files || !this.files[0]) return;

                    let selectedFile = this.files[0];
                    const normalizedName = selectedFile.name.replace(/\.(jpe?g|png)\.(jpe?g|png)$/i, '.$1');

                    if (normalizedName !== selectedFile.name && typeof DataTransfer !== 'undefined') {
                        const transfer = new DataTransfer();
                        selectedFile = new File([selectedFile], normalizedName, {
                            type: selectedFile.type,
                            lastModified: selectedFile.lastModified,
                        });
                        transfer.items.add(selectedFile);
                        this.files = transfer.files;
                    }

                    const preview = this.parentElement.querySelector('[data-pas-foto-preview]');
                    if (!preview) return;

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(selectedFile);
                "
            >
            <small class="text-muted">{{ $isEdit ? 'Kosongkan jika tidak ingin mengganti foto.' : 'Format JPG/JPEG/PNG, maksimal 2 MB.' }}</small>
            @error('pas_foto') <small class="text-danger d-block">{{ $message }}</small> @enderror
            <div wire:loading wire:target="pas_foto" class="small text-muted mt-1">Mengunggah foto...</div>
            <div class="mt-2" wire:ignore>
                <img
                    data-pas-foto-preview
                    src="{{ $existingPasFotoUrl ?? '' }}"
                    alt="Preview pas foto"
                    style="width: 90px; height: 110px; object-fit: cover; border-radius: 14px; border: 1px solid #d8e3ef; {{ $existingPasFotoUrl ? '' : 'display:none;' }}"
                >
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap justify-end gap-3">
        <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save,pas_foto">
            <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
            <span wire:loading wire:target="save">Memproses...</span>
        </button>
        <a href="{{ $returnTo }}" class="btn btn-cancel">Batal</a>
    </div>
</form>
