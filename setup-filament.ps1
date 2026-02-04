# PowerShell script to set up Laravel Filament
# This script will help you install and configure Filament with your Laravel project

Write-Host "Laravel Filament Setup Script" -ForegroundColor Cyan
Write-Host "==============================`n" -ForegroundColor Cyan

# Check if Composer is installed
$composer = Get-Command composer -ErrorAction SilentlyContinue

if (-not $composer) {
    Write-Host "ERROR: Composer is not installed or not in PATH" -ForegroundColor Red
    Write-Host "Please install Composer from: https://getcomposer.org/download/" -ForegroundColor Yellow
    exit 1
}

Write-Host "✓ Composer found" -ForegroundColor Green
Write-Host ""

# Step 1: Install Filament
Write-Host "Step 1: Installing Filament..." -ForegroundColor Yellow
Write-Host "This may take a few minutes..." -ForegroundColor Gray
Write-Host ""

composer require filament/filament:"^3.*" -W

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to install Filament" -ForegroundColor Red
    exit 1
}

Write-Host "`n✓ Filament installed successfully" -ForegroundColor Green
Write-Host ""

# Step 2: Install Admin Panel
Write-Host "Step 2: Setting up Filament Admin Panel..." -ForegroundColor Yellow
php artisan filament:install --panels=admin

Write-Host "`n✓ Admin panel set up" -ForegroundColor Green
Write-Host ""

# Step 3: Run migrations
Write-Host "Step 3: Running migrations..." -ForegroundColor Yellow
php artisan migrate

Write-Host "`n✓ Migrations completed" -ForegroundColor Green
Write-Host ""

# Display next steps
Write-Host "Installation Complete!" -ForegroundColor Cyan
Write-Host "==============================`n" -ForegroundColor Cyan

Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Create an admin user:"
Write-Host "   php artisan make:filament-user"
Write-Host ""
Write-Host "2. Generate resources for your models:"
Write-Host "   php artisan make:filament-resource Alat --generate"
Write-Host "   php artisan make:filament-resource Kategori --generate"
Write-Host "   php artisan make:filament-resource Peminjaman --generate"
Write-Host "   php artisan make:filament-resource Pengguna --generate"
Write-Host ""
Write-Host "3. Start the development server:"
Write-Host "   php artisan serve"
Write-Host ""
Write-Host "4. Access the admin panel at:"
Write-Host "   http://localhost:8000/admin" -ForegroundColor Cyan
Write-Host ""

Write-Host "For detailed setup instructions, see: FILAMENT_SETUP.md" -ForegroundColor Green
