<?php
namespace App\Helpers;

use App\Models\LogAktivitas;

class LogHelper
{
    public static function log($jenis, $keterangan)
    {
        $user = session();
        LogAktivitas::create([
            'nama' => $user->get('pengguna_nama', 'Unknown'),
            'role' => $user->get('pengguna_role', 'Unknown'),
            'jenis' => $jenis,
            'keterangan' => $keterangan,
        ]);
    }
}