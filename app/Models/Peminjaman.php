<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'pengguna_id',
        'alat_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'catatan',
        'tanggal_dikembalikan',
        'hari_keterlambatan',
        'denda',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
        'denda' => 'float',
    ];

    /**
     * Relationship: peminjaman belongs to pengguna
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Relationship: peminjaman belongs to alat
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    /**
     * Scope: filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: filter by pengguna_id
     */
    public function scopeByPengguna($query, $pengguna_id)
    {
        return $query->where('pengguna_id', $pengguna_id);
    }

    /**
     * Scope: get pending peminjaman
     */
    public function scopePending($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope: get approved peminjaman
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    /**
     * Check if peminjaman is pending
     */
    public function isPending()
    {
        return $this->status === 'menunggu';
    }

    /**
     * Check if peminjaman is approved
     */
    public function isApproved()
    {
        return $this->status === 'disetujui';
    }

    /**
     * Check if peminjaman is rejected
     */
    public function isRejected()
    {
        return $this->status === 'ditolak';
    }

    /**
     * Check if peminjaman is returned
     */
    public function isReturned()
    {
        return $this->status === 'dikembalikan';
    }

    /**
     * Get status label with styling
     */
    public function getStatusBadgeAttribute()
    {
        $status = [
            'menunggu' => 'warning',
            'disetujui' => 'success',
            'ditolak' => 'danger',
            'dikembalikan' => 'info',
        ];

        return $status[$this->status] ?? 'secondary';
    }

    /**
     * Check if peminjaman is late
     */
    public function isLate()
    {
        return $this->hari_keterlambatan > 0;
    }

    /**
     * Calculate keterlambatan based on tanggal_dikembalikan
     */
    public function calculateKeterlambatan($tanggalDikembalikan = null)
    {
        $tanggalDikembalikan = $tanggalDikembalikan ?? now()->toDateString();
        $tanggalKembali = $this->tanggal_kembali->toDateString();

        $keterlambatan = \Carbon\Carbon::parse($tanggalDikembalikan)->diffInDays(
            \Carbon\Carbon::parse($tanggalKembali),
            false
        );

        return max(0, $keterlambatan); // Return 0 jika tidak terlambat
    }

    /**
     * Calculate denda based on tarif per hari
     */
    public function calculateDenda($tarifPerHari, $hari_keterlambatan = null)
    {
        if ($hari_keterlambatan === null) {
            $hari_keterlambatan = $this->hari_keterlambatan;
        }

        return $hari_keterlambatan * $tarifPerHari;
    }}