<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ReturnsToIndex;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SiswaForm extends Component
{
    use ReturnsToIndex, WithFileUploads;

    public ?Siswa $siswa = null;

    public string $nama = '';
    public string $nis = '';
    public string $nisn = '';
    public string $tempat_lahir = '';
    public string $tanggal_lahir = '';
    public string $agama = '';
    public string $jenis_kelamin = 'L';
    public string $nama_ayah = '';
    public string $nama_ibu = '';
    public string $nama_wali = '';
    public string $alamat = '';
    public string $nomor_kontak = '';
    public string $kelas_id = '';
    public string $tahun_ajaran_id = '';
    public string $username = '';
    public string $password = '';
    public $pas_foto;
    public ?string $existingPasFotoUrl = null;

    // Isi form dengan data lama ketika halaman digunakan untuk mengedit siswa.
    public function mount(?Siswa $siswa = null): void
    {

        $this->initializeReturnTo(route('admin.siswa.index'));
$this->siswa = $siswa;

        if ($this->isEditing()) {
            $this->nama = $siswa->nama ?? '';
            $this->nis = $siswa->nis ?? '';
            $this->nisn = $siswa->nisn ?? '';
            $this->tempat_lahir = $siswa->tempat_lahir ?? '';
            $this->tanggal_lahir = $siswa->tanggal_lahir?->format('Y-m-d') ?? '';
            $this->agama = $siswa->agama ?? '';
            $this->jenis_kelamin = $siswa->jenis_kelamin ?? 'L';
            $this->nama_ayah = $siswa->nama_ayah ?? '';
            $this->nama_ibu = $siswa->nama_ibu ?? '';
            $this->nama_wali = $siswa->nama_wali ?? '';
            $this->alamat = $siswa->alamat ?? '';
            $this->nomor_kontak = $siswa->nomor_telepon ?? '';
            $this->kelas_id = (string) $siswa->kelas_id;
            $this->tahun_ajaran_id = (string) ($siswa->tahun_ajaran_id ?? '');
            $this->username = $siswa->user?->username ?? '';
            $this->existingPasFotoUrl = $siswa->pas_foto_url;
            return;
        }

        $this->tahun_ajaran_id = (string) optional(TahunAjaran::where('is_active', true)->first())->id;
    }

    // Validasi lalu simpan data siswa beserta akun orang tua.
    public function save()
    {
        $hadUserAccount = (bool) $this->siswa?->user_id;
        $data = $this->validate($this->rules(), $this->messages());
        $nomorKontak = $this->nullableString($data['nomor_kontak']);

        if ($this->isEditing()) {
            DB::transaction(function () use ($data, $nomorKontak) {
                $this->siswa->loadMissing('user');
                $pasFoto = $this->siswa->pas_foto;

                // Ganti file pas foto lama hanya jika pengguna memilih foto baru.
                if ($this->pas_foto) {
                    if ($pasFoto) {
                        Storage::disk('public')->delete($pasFoto);
                    }
                    $pasFoto = $this->pas_foto->store('pas-foto-siswa', 'public');
                }

                $this->siswa->update([
                    'nama' => $data['nama'],
                    'nis' => $this->nullableString($data['nis']),
                    'nisn' => $this->nullableString($data['nisn']),
                    'tempat_lahir' => $this->nullableString($data['tempat_lahir']),
                    'tanggal_lahir' => $data['tanggal_lahir'] ?: null,
                    'agama' => $this->nullableString($data['agama']),
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'nama_ayah' => $data['nama_ayah'],
                    'nama_ibu' => $data['nama_ibu'],
                    'nama_wali' => $this->nullableString($data['nama_wali'] ?? ''),
                    'alamat' => $data['alamat'],
                    'nomor_telepon' => $nomorKontak,
                    'pas_foto' => $pasFoto,
                    'kelas_id' => (int) $data['kelas_id'],
                    'tahun_ajaran_id' => (int) $data['tahun_ajaran_id'],
                ]);

                if ($this->siswa->user) {
                    $this->siswa->user->update([
                        'name' => $data['nama'],
                    ]);
                } else {
                    $user = User::create([
                        'username' => $data['username'],
                        'name' => $data['nama'],
                        'role' => 'orang_tua',
                        'password' => $data['password'],
                    ]);

                    $this->siswa->update([
                        'user_id' => $user->id,
                    ]);
                }
            });

            session()->flash('success', $hadUserAccount
                ? 'Data siswa berhasil diubah.'
                : 'Data siswa berhasil diubah dan akun orang tua baru berhasil dibuat.');

            return $this->redirectToIndex();
        }

        DB::transaction(function () use ($data, $nomorKontak) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['nama'],
                'role' => 'orang_tua',
                'password' => $data['password'],
            ]);

            $pasFoto = $this->pas_foto
                ? $this->pas_foto->store('pas-foto-siswa', 'public')
                : null;

            Siswa::create([
                'nama' => $data['nama'],
                'nis' => $this->nullableString($data['nis']),
                'nisn' => $this->nullableString($data['nisn']),
                'tempat_lahir' => $this->nullableString($data['tempat_lahir']),
                'tanggal_lahir' => $data['tanggal_lahir'] ?: null,
                'agama' => $this->nullableString($data['agama']),
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_ayah' => $data['nama_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'nama_wali' => $this->nullableString($data['nama_wali'] ?? ''),
                'alamat' => $data['alamat'],
                'nomor_telepon' => $nomorKontak,
                'pas_foto' => $pasFoto,
                'kelas_id' => (int) $data['kelas_id'],
                'tahun_ajaran_id' => (int) $data['tahun_ajaran_id'],
                'user_id' => $user->id,
            ]);
        });

        session()->flash('success', 'Siswa dan akun orang tua berhasil ditambahkan.');

        return $this->redirectToIndex();
    }

    // Aturan akun dibedakan antara proses tambah dan edit siswa.
    private function rules(): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:40'],
            'nis' => ['nullable', 'string', 'max:11'],
            'nisn' => ['nullable', 'string', 'max:10'],
            'tempat_lahir' => ['nullable', 'string', 'max:20'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nama_ayah' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ibu,nama_wali'],
            'nama_ibu' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ayah,nama_wali'],
            'nama_wali' => ['nullable', 'string', 'max:40', 'required_without_all:nama_ayah,nama_ibu'],
            'alamat' => ['required', 'string'],
            'nomor_kontak' => ['nullable', 'string', 'max:13'],
            'pas_foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
        ];

        if ($this->isEditing()) {
            if (! $this->siswa?->user_id) {
                $rules['username'] = ['required', 'string', 'max:50', Rule::unique('users', 'username')];
                $rules['password'] = ['required', 'string', 'min:8'];
            }

            return $rules;
        }

        $rules['username'] = ['required', 'string', 'max:50', Rule::unique('users', 'username')];
        $rules['password'] = ['required', 'string', 'min:8'];

        return $rules;
    }

    private function messages(): array
    {
        return [
            'username.unique' => 'Username sudah digunakan.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'pas_foto.image' => 'Pas foto harus berupa gambar.',
            'pas_foto.mimes' => 'Pas foto harus berformat JPG, JPEG, atau PNG.',
            'nama_ayah.required_without_all' => 'Isi minimal salah satu nama ayah, nama ibu, atau nama wali.',
            'nama_ibu.required_without_all' => 'Isi minimal salah satu nama ayah, nama ibu, atau nama wali.',
            'nama_wali.required_without_all' => 'Isi minimal salah satu nama ayah, nama ibu, atau nama wali.',
        ];
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function isEditing(): bool
    {
        return $this->siswa instanceof Siswa && $this->siswa->exists;
    }

    public function render()
    {
        return view('livewire.admin.siswa-form', [
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'tahunAjaran' => TahunAjaran::orderByDesc('is_active')->orderByDesc('tahun_ajaran')->get(),
            'isEdit' => $this->isEditing(),
        ]);
    }
}

