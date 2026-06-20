<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Definisikan relasi model untuk data gurus.
    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }

    // Definisikan relasi model untuk data siswas.
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    // Definisikan relasi model untuk data perkembangans.
    public function perkembangans()
    {
        return $this->hasMany(Perkembangan::class);
    }
}
