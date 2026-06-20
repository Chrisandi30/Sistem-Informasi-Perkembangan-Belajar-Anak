@php
    // View: resources/views/livewire/guru/perkembangan-form.blade.php
@endphp
{{-- Form untuk menerima dan mengirim data pengguna. --}}
<form wire:submit="save" class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-4 p-lg-5">
        <div class="mb-5">
            <input type="hidden" wire:model="siswa_id">
            <div class="grid gap-3 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="form-label fw-bold">Siswa</label>
                    <div class="form-control rounded-4 bg-[#f8fbff] px-4 py-3" style="font-weight:800; color:#1d2533; min-height:56px;">
                        {{ $selectedSiswa?->nama ?? '-' }}
                    </div>
                    @error('siswa_id') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label fw-bold">Bulan</label>
                    <select wire:model.defer="bulan" class="form-select rounded-4" required>
                        @foreach($monthOptions as $monthNumber => $monthName)
                            <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                        @endforeach
                    </select>
                    @error('bulan') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label fw-bold">Tahun</label>
                    <select wire:model.defer="tahun" class="form-select rounded-4" required>
                        @foreach($yearOptions as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('tahun') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        @foreach($groupedDetailAspek as $kategori => $items)
            <section class="mb-4 @if(! $loop->first) border-top pt-5 mt-5 @endif" style="border-color:#edf1f6 !important;">
                <h3 class="mb-3 text-[18px] font-extrabold text-[#1d2533]">{{ $kategori }}</h3>

                <div>
                    @foreach($items as $item)
                        <div class="mb-5">
                            <input type="hidden" wire:model="detail_aspek.{{ $item['index'] }}.kategori_aspek">
                            <input type="hidden" wire:model="detail_aspek.{{ $item['index'] }}.nama_aspek">
                            <input type="hidden" wire:model="detail_aspek.{{ $item['index'] }}.mata_pelajaran_id">
                            <input type="hidden" wire:model="detail_aspek.{{ $item['index'] }}.perkembangan_non_akademis_id">

                            <div class="mb-2 text-[17px] font-bold text-[#1d2533]">{{ $item['nama_aspek'] }}</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Hal yang Sudah Berkembang</label>
                                    <textarea wire:model.defer="detail_aspek.{{ $item['index'] }}.hal_berkembang" class="form-control rounded-4" rows="4" style="text-align: justify;"></textarea>
                                    @error('detail_aspek.'.$item['index'].'.hal_berkembang') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Hal yang Perlu Diperhatikan</label>
                                    <textarea wire:model.defer="detail_aspek.{{ $item['index'] }}.perlu_diperhatikan" class="form-control rounded-4" rows="4" style="text-align: justify;"></textarea>
                                    @error('detail_aspek.'.$item['index'].'.perlu_diperhatikan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <section class="mb-2">
            <div class="mb-2 text-[18px] font-extrabold text-[#1d2533]">Catatan</div>
            <textarea wire:model.defer="catatan_pengembangan" class="form-control rounded-4" rows="4" style="text-align: justify;"></textarea>
            @error('catatan_pengembangan') <small class="text-danger">{{ $message }}</small> @enderror
        </section>

        <div class="mt-6 flex flex-wrap justify-end gap-3">
            <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</span>
                <span wire:loading wire:target="save">Memproses...</span>
            </button>
            <a href="{{ $returnTo }}" class="btn btn-cancel">Batal</a>
        </div>
    </div>
</form>
