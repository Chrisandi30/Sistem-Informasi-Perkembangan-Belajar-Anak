{{-- Form untuk menerima dan mengirim data pengguna. --}}
<form wire:submit="save" class="card card-body form-shell bg-white p-7">
    <style>
        /* Ikon tanggal khusus form ini agar konsisten pada browser HP. */
        .mobile-date-icon {
            display: none;
        }

        @media (max-width: 700px) {
            .mobile-date-field {
                position: relative !important;
                display: block !important;
                width: 100% !important;
            }

            .mobile-date-field .form-control {
                width: 100% !important;
                padding-right: 46px !important;
                box-sizing: border-box !important;
            }

            .mobile-date-field input[type="date"]::-webkit-calendar-picker-indicator {
                opacity: 0 !important;
                width: 32px !important;
                height: 32px !important;
                cursor: pointer;
            }

            .mobile-date-field .mobile-date-icon {
                position: absolute !important;
                top: 50% !important;
                right: 16px !important;
                z-index: 2 !important;
                display: block !important;
                transform: translateY(-50%) !important;
                color: #1f2937 !important;
                font-size: 17px !important;
                line-height: 1 !important;
                pointer-events: none !important;
            }
        }
    </style>
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="mb-3 md:mb-0">
            <label class="form-label">Judul</label>
            <input type="text" wire:model.defer="judul" class="form-control" required>
            @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Tanggal Terbit</label>
            <div class="mobile-date-field">
                <input type="date" wire:model.defer="tanggal_terbit" class="form-control" required>
                <i class="far fa-calendar mobile-date-icon" aria-hidden="true"></i>
            </div>
            @error('tanggal_terbit') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3 md:mb-0">
            <label class="form-label">Tanggal Berakhir</label>
            <div class="mobile-date-field">
                <input type="date" wire:model.defer="tanggal_berakhir" class="form-control" required>
                <i class="far fa-calendar mobile-date-icon" aria-hidden="true"></i>
            </div>
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
        <a href="{{ $returnTo }}" class="btn btn-cancel">Batal</a>
    </div>
</form>


