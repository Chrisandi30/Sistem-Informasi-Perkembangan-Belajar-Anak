<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPerkembangan extends Model
{
    use HasFactory;

    protected $table = 'detail_perkembangans';

    protected $fillable = [
        'perkembangan_id',
        'mata_pelajaran_id',
        'perkembangan_non_akademis_id',
        'kategori_aspek',
        'nama_aspek',
        'hal_berkembang',
        'perlu_diperhatikan',
        'urutan',
    ];

    // Siapkan data laporan perkembangan.
    public function perkembangan()
    {
        return $this->belongsTo(Perkembangan::class);
    }

    // Definisikan relasi model untuk data mataPelajaran.
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Definisikan relasi model untuk data perkembanganNonAkademis.
    public function perkembanganNonAkademis()
    {
        return $this->belongsTo(PerkembanganNonAkademis::class);
    }
}
