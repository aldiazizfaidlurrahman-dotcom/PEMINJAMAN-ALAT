<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    // Nama tabel
    protected $table = 'kategori';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable
    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi: Satu kategori memiliki banyak alat
     */
    public function alats(): HasMany
    {
        return $this->hasMany(Alat::class, 'kategori_id');
    }

    /**
     * Scope: Cari berdasarkan nama kategori
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama_kategori', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
