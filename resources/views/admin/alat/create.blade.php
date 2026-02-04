@extends('admin.layout')

@section('title', 'Tambah Alat')

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

    /* Custom Styling for Select */
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; }

    /* 5. Buttons */
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

    .error-text { color: #ef4444; font-size: 0.75rem; margin-top: 6px; display: block; }
</style>

<div class="content-wrapper">
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 1.5rem; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 8px;">
        <span>Dashboard</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span>Data Alat</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span style="color: #1e293b; font-weight: 600;">Tambah Alat</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h3>Tambah Alat Baru</h3>
            <p>Masukkan informasi peralatan baru ke dalam sistem inventaris.</p>
        </div>

        <form action="{{ route('admin.alat.store') }}" method="POST" id="alatForm">
            @csrf
            
            <div class="grid">
                {{-- Nama Alat --}}
                <div class="form-group">
                    <label for="nama_alat">Nama Alat</label>
                    <input type="text" id="nama_alat" name="nama_alat" class="form-control" placeholder="Contoh: Kamera Canon EOS" value="{{ old('nama_alat') }}" required autofocus>
                    @error('nama_alat') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" @if(old('kategori_id') == $kat->id) selected @endif>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Kondisi --}}
                <div class="form-group">
                    <label for="kondisi">Kondisi Alat</label>
                    <select id="kondisi" name="kondisi" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Kondisi --</option>
                        <option value="baik" @if(old('kondisi') === 'baik') selected @endif>Baik</option>
                        <option value="rusak" @if(old('kondisi') === 'rusak') selected @endif>Rusak</option>
                        <option value="diperbaiki" @if(old('kondisi') === 'diperbaiki') selected @endif>Diperbaiki</option>
                    </select>
                    @error('kondisi') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                {{-- Stok --}}
                <div class="form-group">
                    <label for="stok">Jumlah Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control" placeholder="0" value="{{ old('stok') }}" min="0" required>
                    @error('stok') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Simpan Alat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Loading Feedback
    document.getElementById('alatForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = 'Menyimpan...';
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
    };
</script>
@endsection