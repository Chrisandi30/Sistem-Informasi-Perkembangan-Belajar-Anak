<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkembanganNonAkademis extends Model
{
    use HasFactory;

    protected $table = 'perkembangan_non_akademis';

    protected $fillable = [
        'kategori_aspek',
        'nama_aspek',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Definisikan relasi model untuk data detailPerkembangans.
    public function detailPerkembangans()
    {
        return $this->hasMany(DetailPerkembangan::class);
    }
}
