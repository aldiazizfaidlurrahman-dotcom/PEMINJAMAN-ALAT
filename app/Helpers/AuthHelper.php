<?php

namespace App\Helpers;

class AuthHelper
{
    /**
     * Cek apakah user sudah login
     */
    public static function isLoggedIn()
    {
        return session('pengguna_id') !== null;
    }

    /**
     * Ambil ID pengguna yang login
     */
    public static function getUserId()
    {
        return session('pengguna_id');
    }

    /**
     * Ambil nama pengguna yang login
     */
    public static function getUserName()
    {
        return session('pengguna_nama');
    }

    /**
     * Ambil username pengguna yang login
     */
    public static function getUsername()
    {
        return session('pengguna_username');
    }

    /**
     * Ambil role pengguna yang login
     */
    public static function getRole()
    {
        return session('pengguna_role');
    }

    /**
     * Cek apakah pengguna adalah admin
     */
    public static function isAdmin()
    {
        return static::getRole() === 'admin';
    }

    /**
     * Cek apakah pengguna adalah petugas
     */
    public static function isPetugas()
    {
        return static::getRole() === 'petugas';
    }

    /**
     * Cek apakah pengguna adalah peminjam
     */
    public static function isPeminjam()
    {
        return static::getRole() === 'peminjam';
    }
}
