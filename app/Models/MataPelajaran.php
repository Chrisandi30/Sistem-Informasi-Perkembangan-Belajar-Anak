<?php

// Model: app/Models/MataPelajaran.php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajarans';

    protected $fillable = ['nama_mapel', 'kelas_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Siapkan data laporan berdasarkan kelas.
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
