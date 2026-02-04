<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pengguna;
use App\Models\Kategori;
use App\Models\Alat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed tabel pengguna dengan data login
        Pengguna::create([
            'nama' => 'Admin Sistem',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => true,
        ]);

        Pengguna::create([
            'nama' => 'Petugas Alat',
            'username' => 'petugas',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'status' => true,
        ]);

        Pengguna::create([
            'nama' => 'Budi Santoso',
            'username' => 'budi',
            'password' => Hash::make('budi123'),
            'role' => 'peminjam',
            'status' => true,
        ]);

        Pengguna::create([
            'nama' => 'Siti Nurhaliza',
            'username' => 'siti',
            'password' => Hash::make('siti123'),
            'role' => 'peminjam',
            'status' => true,
        ]);

        // Seed Kategori
        Kategori::create(['nama_kategori' => 'Elektronik']);
        Kategori::create(['nama_kategori' => 'Perabotan']);
        Kategori::create(['nama_kategori' => 'Perlengkapan Kantor']);
        Kategori::create(['nama_kategori' => 'Peralatan Laboratorium']);

        // Seed Alat
        Alat::create([
            'kategori_id' => 1,
            'nama_alat' => 'Laptop Dell',
            'kondisi' => 'baik',
            'stok' => 5,
        ]);

        Alat::create([
            'kategori_id' => 1,
            'nama_alat' => 'Proyektor',
            'kondisi' => 'baik',
            'stok' => 3,
        ]);

        Alat::create([
            'kategori_id' => 1,
            'nama_alat' => 'Speaker',
            'kondisi' => 'rusak',
            'stok' => 2,
        ]);

        Alat::create([
            'kategori_id' => 2,
            'nama_alat' => 'Meja Kerja',
            'kondisi' => 'baik',
            'stok' => 10,
        ]);

        Alat::create([
            'kategori_id' => 2,
            'nama_alat' => 'Kursi Kantor',
            'kondisi' => 'baik',
            'stok' => 15,
        ]);

        Alat::create([
            'kategori_id' => 3,
            'nama_alat' => 'Printer HP',
            'kondisi' => 'baik',
            'stok' => 2,
        ]);

        Alat::create([
            'kategori_id' => 3,
            'nama_alat' => 'Mesin Fotokopi',
            'kondisi' => 'diperbaiki',
            'stok' => 1,
        ]);

        Alat::create([
            'kategori_id' => 4,
            'nama_alat' => 'Mikroskop Digital',
            'kondisi' => 'baik',
            'stok' => 4,
        ]);

        Alat::create([
            'kategori_id' => 4,
            'nama_alat' => 'Beaker Glass Set',
            'kondisi' => 'baik',
            'stok' => 20,
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

