<?php

// Model: app/Models/Guru.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nuptk',
        'jenis_kelamin',
        'alamat',
        'jenjang_pendidikan',
        'user_id',
        'kelas_id',
    ];

    // Definisikan relasi model untuk data user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Siapkan data laporan berdasarkan kelas.
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Definisikan relasi model untuk data perkembangans.
    public function perkembangans()
    {
        return $this->hasMany(Perkembangan::class);
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
}
