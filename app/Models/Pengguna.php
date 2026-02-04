<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class Pengguna extends Model implements AuthenticatableContract
{
    use Authenticatable;

    // Nama tabel
    protected $table = 'pengguna';

    // Primary key
    protected $primaryKey = 'id';

    // Non-incrementing untuk UUID (jika perlu), biarkan true untuk auto-increment
    public $incrementing = true;

    // Cast tipe data
    protected $casts = [
        'status' => 'boolean',
    ];

    // Mass assignable
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'status',
    ];

    // Hidden dari JSON
    protected $hidden = [
        'password',
    ];

    /**
     * Cek apakah pengguna aktif
     */
    public function isActive()
    {
        return $this->status === true;
    }

    /**
     * Cek apakah pengguna adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna adalah petugas
     */
    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    /**
     * Cek apakah pengguna adalah peminjam
     */
    public function isPeminjam()
    {
        return $this->role === 'peminjam';
    }

    /**
     * Relasi: Pengguna dapat memiliki banyak peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'pengguna_id');
    }
}
