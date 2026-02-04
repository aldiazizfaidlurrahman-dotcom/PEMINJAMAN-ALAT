@echo off
REM Create Filament Resources for the models

echo Creating Filament Resources...

echo Creating Alat Resource...
php artisan make:filament-resource Alat --generate

echo Creating Kategori Resource...
php artisan make:filament-resource Kategori --generate

echo Creating Peminjaman Resource...
php artisan make:filament-resource Peminjaman --generate

echo Creating Pengguna Resource...
php artisan make:filament-resource Pengguna --generate

echo.
echo Resources created successfully!
echo Access the admin panel at: http://localhost:8000/admin

pause
