@extends('admin.layout')

@section('title', 'Tambah Kategori')

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
        max-width: 600px; /* Lebih ramping karena input sedikit */
        margin: 0 auto; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* 3. Typography */
    .form-header { text-align: left; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem; }
    .form-header h3 { color: #0f172a; font-size: 1.5rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; }
    .form-header p { color: #64748b; font-size: 0.875rem; margin-top: 4px; }

    /* 4. Form Groups */
    .form-group { margin-bottom: 1.5rem; }
    label { 
        display: block; 
        margin-bottom: 8px; 
        font-size: 0.875rem; 
        font-weight: 600; 
        color: #334155; 
    }
    
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

    /* 5. Buttons */
    .btn-group { 
        display: flex; 
        justify-content: flex-end; 
        gap: 12px; 
        margin-top: 1rem; 
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

    .error-text { color: #ef4444; font-size: 0.75rem; margin-top: 6px; display: block; }
</style>

<div class="content-wrapper">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 1.5rem; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
        <span>Dashboard</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span>Kategori</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span style="color: #1e293b; font-weight: 600;">Tambah Baru</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h3>Tambah Kategori Baru</h3>
            <p>Buat kategori baru untuk mengelompokkan peralatan inventaris Anda.</p>
        </div>

        <form action="{{ route('admin.kategori.store') }}" method="POST" id="kategoriForm">
            @csrf

            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" placeholder="Contoh: Kamera, Elektronik, Alat Musik" value="{{ old('nama_kategori') }}" required autofocus>
                @error('nama_kategori') 
                    <span class="error-text">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline; margin-right:4px;"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Loading Feedback
    document.getElementById('kategoriForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = 'Menyimpan...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    };
</script>
@endsection