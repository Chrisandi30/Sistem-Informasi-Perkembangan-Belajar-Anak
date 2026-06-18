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

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function detailPerkembangans()
    {
        return $this->hasMany(DetailPerkembangan::class)->orderBy('urutan');
    }

    public function groupedDetailsByCategory()
    {
        return ($this->relationLoaded('detailPerkembangans')
            ? $this->detailPerkembangans
            : $this->detailPerkembangans()->get())
            ->groupBy('kategori_aspek');
    }
}
