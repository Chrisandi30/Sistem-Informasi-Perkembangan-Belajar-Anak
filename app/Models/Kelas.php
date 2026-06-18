<?php

// Model: app/Models/Kelas.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama_kelas'];

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function mataPelajarans()
    {
        return $this->hasMany(MataPelajaran::class);
    }

    public function perkembangans()
    {
        return $this->hasMany(Perkembangan::class);
    }
}
