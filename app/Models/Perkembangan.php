<?php

// Model: app/Models/Perkembangan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Perkembangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'kelas_id',
        'bulan',
        'tahun',
        'status',
        'validated_by',
        'validated_at',
        'catatan_validasi',
        'catatan_pengembangan',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // Definisikan relasi model untuk data siswa.
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Definisikan relasi model untuk data guru.
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Siapkan data laporan berdasarkan kelas.
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Definisikan relasi model untuk data validator.
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Definisikan relasi model untuk data detailPerkembangans.
    public function detailPerkembangans()
    {
        return $this->hasMany(DetailPerkembangan::class)->orderBy('urutan');
    }

    // Kelompokkan detail perkembangan berdasarkan kategori.
    public function groupedDetailsByCategory()
    {
        return ($this->relationLoaded('detailPerkembangans')
            ? $this->detailPerkembangans
            : $this->detailPerkembangans()->get())
            ->groupBy('kategori_aspek');
    }
}
