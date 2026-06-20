<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama_kelas'];

    // Definisikan relasi model untuk data guru.
    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }

    // Definisikan relasi model untuk data siswa.
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    // Definisikan relasi model untuk data mataPelajaran.
    public function mataPelajarans()
    {
        return $this->hasMany(MataPelajaran::class);
    }

    // Definisikan relasi model untuk data perkembangan.
    public function perkembangans()
    {
        return $this->hasMany(Perkembangan::class);
    }
}
