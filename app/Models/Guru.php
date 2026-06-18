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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function perkembangans()
    {
        return $this->hasMany(Perkembangan::class);
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki Laki',
            'P' => 'Perempuan',
            default => (string) $this->jenis_kelamin,
        };
    }
}
