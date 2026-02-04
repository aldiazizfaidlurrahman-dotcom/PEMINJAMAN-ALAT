<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alat extends Model
{
    // Nama tabel
    protected $table = 'alat';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'kondisi',
        'stok',
    ];

    // Cast tipe data
    protected $casts = [
        'stok' => 'integer',
    ];

    /**
     * Relasi: Alat milik satu kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi: Alat dapat dipinjam berkali-kali
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'alat_id');
    }

    /**
     * Scope: Cari berdasarkan nama alat
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama_alat', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Scope: Filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategoriId)
    {
        if ($kategoriId) {
            return $query->where('kategori_id', $kategoriId);
        }
        return $query;
    }
}
