@extends('admin.layout')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<style>
    .content-wrapper { padding: 2.5rem; font-family: 'Inter', -apple-system, sans-serif; background-color: #f8fafc; min-height: 100vh; }
    .form-card { background: #fff; border-radius: 16px; padding: 2.5rem; border: 1px solid #e2e8f0; max-width: 850px; margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);}
    .form-header { text-align: left; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem; }
    .form-header h3 { color: #0f172a; font-size: 1.5rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; }
    .form-header p { color: #64748b; font-size: 0.875rem; margin-top: 4px; }
    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 1.25rem; }
    label { display: block; margin-bottom: 8px; font-size: 0.875rem; font-weight: 600; color: #334155; }
    .input-wrapper { position: relative; }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.875rem; transition: all 0.2s; color: #1e293b; background: #fff; box-sizing: border-box; }
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); outline: none; }
    .form-control::placeholder { color: #94a3b8; }
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; }
    .btn-group { display: flex; justify-content: flex-end; gap: 12px; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; }
    .btn { padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-secondary { background: #f1f5f9; color: #475569; border: none; text-decoration: none; }
    .btn-secondary:hover { background: #e2e8f0; color: #1e293b; }
    .btn-primary { background: #2563eb; color: white; border: none; }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
    .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; background: none; border: none; cursor: pointer; padding: 4px; }
    .password-toggle:hover { color: #64748b; }
</style>

<div class="content-wrapper">
    <div style="margin-bottom: 1.5rem; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
        <span>Dashboard</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span>Pengguna</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span style="color: #1e293b; font-weight: 600;">Tambah Baru</span>
    </div>

    @if(session('success')) 
        <div style="padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 10px;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span style="font-weight: 500;">{{ session('success') }}</span>
        </div> 
    @endif

    <div class="form-card">
        <div class="form-header">
            <h3>Tambah Pengguna Baru</h3>
            <p>Silakan isi detail akun untuk mendaftarkan pengguna baru ke sistem.</p>
        </div>

        <form action="{{ route('admin.pengguna.store') }}" method="POST" id="userForm">
            @csrf
            <div class="grid">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required autofocus>
                    </div>
                    @error('nama') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" class="form-control" placeholder="username.pengguna" value="{{ old('username') }}" required>
                    </div>
                    @error('username') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <svg id="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                    @error('password_confirmation') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="role">Hak Akses (Role)</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Hak Akses</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                    </select>
                    @error('role') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status Akun</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Password Visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L5.93 5.93m7.444 7.444l3.95 3.95M10.517 5.039A9.87 9.87 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>';
        }
    }

    // Auto-generate Username based on Name
    const namaInput = document.getElementById('nama');
    const usernameInput = document.getElementById('username');
    if(namaInput && usernameInput) {
        namaInput.addEventListener('input', function(e) {
            usernameInput.value = e.target.value
                .toLowerCase()
                .replace(/\s+/g, '.')
                .replace(/[^a-z0-9.]/g, '');
        });
    }

    // Loading state on submit
    document.getElementById('userForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = 'Menyimpan...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    };
</script>
@endsection