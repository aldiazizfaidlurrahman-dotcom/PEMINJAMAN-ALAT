@extends('admin.layout')

@section('title', 'Edit Pengguna')

@section('content')
<style>
    /* 1. Page & Layout */
    .content-wrapper { 
        padding: 2.5rem; 
        font-family: 'Inter', -apple-system, sans-serif; 
        background-color: #f8fafc;
        min-height: 100vh;
    }

    /* 2. Form Card Refinement */
    .form-card { 
        background: #ffffff; 
        border-radius: 16px; 
        padding: 2.5rem; 
        border: 1px solid #e2e8f0; 
        max-width: 850px; 
        margin: 0 auto; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* 3. Typography */
    .form-header { text-align: left; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem; }
    .form-header h3 { color: #0f172a; font-size: 1.5rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; }
    .form-header p { color: #64748b; font-size: 0.875rem; margin-top: 4px; }

    /* 4. Grid & Form Groups */
    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }

    .form-group { margin-bottom: 1.25rem; }
    label { 
        display: block; 
        margin-bottom: 8px; 
        font-size: 0.875rem; 
        font-weight: 600; 
        color: #334155; 
    }
    
    /* 5. Modern Inputs */
    .input-wrapper { position: relative; }
    .form-control { 
        width: 100%; 
        padding: 12px 16px; 
        border: 1px solid #cbd5e1; 
        border-radius: 10px; 
        font-size: 0.875rem;
        transition: all 0.2s;
        color: #1e293b;
        background-color: #ffffff;
        box-sizing: border-box;
    }
    .form-control:focus { 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); 
        outline: none; 
    }
    .form-control:disabled { background-color: #f8fafc; color: #94a3b8; cursor: not-allowed; }

    /* Custom Styling for Select */
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; }

    /* 6. Action Buttons */
    .btn-group { 
        display: flex; 
        justify-content: flex-end; 
        gap: 12px; 
        margin-top: 2rem; 
        padding-top: 1.5rem; 
        border-top: 1px solid #f1f5f9; 
    }
    .btn { 
        padding: 12px 24px; 
        border-radius: 10px; 
        font-weight: 600; 
        font-size: 0.875rem; 
        cursor: pointer; 
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-secondary { background: #f1f5f9; color: #475569; border: none; }
    .btn-secondary:hover { background: #e2e8f0; color: #1e293b; }
    
    .btn-primary { background: #2563eb; color: white; border: none; }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* Password Toggle */
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
    }

    .hint-text { font-size: 0.75rem; color: #64748b; margin-top: 6px; display: block; }
</style>

<div class="content-wrapper">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 1.5rem; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
        <span>Dashboard</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span>Pengguna</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span style="color: #1e293b; font-weight: 600;">Edit Pengguna</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h3>Edit Profil Pengguna</h3>
            <p>Perbarui informasi akun dan hak akses pengguna di bawah ini.</p>
        </div>

        <form action="{{ route('admin.pengguna.update', $pengguna->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="grid">
                {{-- Nama --}}
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama', $pengguna->nama) }}" required autofocus>
                    </div>
                    @error('nama') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                {{-- Username --}}
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $pengguna->username) }}" required>
                    </div>
                    @error('username') <small style="color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</small> @enderror
                </div>

                {{-- Password Baru --}}
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                        <button type="button" class="password-toggle" onclick="togglePass('password', 'eye1')">
                            <svg id="eye1" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>
                    <span class="hint-text">Kosongkan jika tidak ingin mengubah password.</span>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••">
                        <button type="button" class="password-toggle" onclick="togglePass('password_confirmation', 'eye2')">
                            <svg id="eye2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Role --}}
                <div class="form-group">
                    <label for="role">Hak Akses (Role)</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="admin" @selected(old('role', $pengguna->role) == 'admin')>Admin</option>
                        <option value="petugas" @selected(old('role', $pengguna->role) == 'petugas')>Petugas</option>
                        <option value="peminjam" @selected(old('role', $pengguna->role) == 'peminjam')>Peminjam</option>
                    </select>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="status">Status Akun</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="1" @selected(old('status', $pengguna->status) == 1)>Aktif</option>
                        <option value="0" @selected(old('status', $pengguna->status) == 0)>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Password Visibility
    function togglePass(inputId, svgId) {
        const input = document.getElementById(inputId);
        const svg = document.getElementById(svgId);
        
        if (input.type === 'password') {
            input.type = 'text';
            svg.style.color = '#2563eb';
        } else {
            input.type = 'password';
            svg.style.color = '#94a3b8';
        }
    }

    // Loading Feedback
    document.getElementById('editForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = 'Menyimpan...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    };
</script>
@endsection