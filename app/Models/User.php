<?php

// Model: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'role',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // Definisikan relasi model untuk data guru.
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    // Definisikan relasi model untuk data siswa.
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }
}
