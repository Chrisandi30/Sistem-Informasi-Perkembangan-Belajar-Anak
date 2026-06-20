<?php

// Model: app/Models/Siswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'alamat',
        'nomor_telepon',
        'pas_foto',
        'kelas_id',
        'tahun_ajaran_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Siapkan data laporan berdasarkan kelas.
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Definisikan relasi model untuk data tahunAjaran.
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Definisikan relasi model untuk data user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisikan relasi model untuk data perkembangans.
    public function perkembangans()
    {
        return $this->hasMany(Perkembangan::class);
    }

    // Bentuk URL publik untuk pas foto siswa.
    public function getPasFotoUrlAttribute(): ?string
    {
        return $this->pas_foto
            ? route('media.public', ['path' => $this->pas_foto])
            : null;
    }

    // Ubah kode jenis kelamin menjadi label yang mudah dibaca.
    public function getJenisKelaminLabelAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki Laki',
            'P' => 'Perempuan',
            default => (string) $this->jenis_kelamin,
        };
    }

    // Gabungkan nama orang tua atau wali siswa.
    public function getNamaOrangTuaLabelAttribute(): string
    {
        $ayah = trim((string) $this->nama_ayah);
        $ibu = trim((string) $this->nama_ibu);
        $wali = trim((string) $this->nama_wali);

        $names = array_values(array_filter([$ayah, $ibu, $wali], fn ($value) => $value !== '' && $value !== '-'));

        return $names === [] ? '-' : implode(', ', $names);
    }
}

