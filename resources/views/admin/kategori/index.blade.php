@extends('admin.layout')

@section('title', 'Data Kategori')

@section('content')

<style>
    /* 1. Global Layout & Refinement */
    .content-wrapper { 
        padding: 2.5rem; 
        font-family: 'Inter', -apple-system, sans-serif; 
        background-color: #f8fafc;
        min-height: 100vh;
    }

    /* 2. Enhanced Card & Table */
    .card-container { 
        background: white; 
        border-radius: 16px; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th { 
        background: #f8fafc; 
        padding: 16px 24px; 
        font-size: 0.75rem; 
        font-weight: 700;
        text-transform: uppercase; 
        letter-spacing: 0.05em;
        color: #64748b; 
        border-bottom: 1px solid #e2e8f0; 
    }
    .modern-table td { 
        padding: 16px 24px; 
        border-bottom: 1px solid #f1f5f9; 
        vertical-align: middle;
        color: #334155;
        font-size: 0.875rem;
    }
    .modern-table tr:hover { background-color: #f1f5f9/50; transition: background 0.2s; }

    /* 3. Search Bar Modernization */
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 16px;
        align-items: center;
        border: 1px solid #e2e8f0;
    }
    .search-wrapper { position: relative; flex-grow: 1; }
    .search-input { 
        width: 100%;
        padding: 12px 16px 12px 44px; 
        border: 1px solid #cbd5e1; 
        border-radius: 10px; 
        transition: all 0.2s;
        font-size: 0.875rem;
    }
    .search-input:focus { 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); 
        outline: none; 
    }

    /* 4. Action Buttons */
    .btn-add { 
        background: #2563eb; 
        color: white; 
        padding: 12px 24px; 
        border-radius: 10px; 
        text-decoration: none; 
        font-weight: 600; 
        display: flex; 
        align-items: center; 
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-add:hover { background: #1d4ed8; transform: translateY(-1px); }
    
    .action-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon { 
        width: 36px; height: 36px; 
        border-radius: 8px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-edit { background: #f1f5f9; color: #475569; text-decoration: none; }
    .btn-edit:hover { background: #e2e8f0; color: #1e293b; }
    .btn-delete { background: #fff1f2; color: #e11d48; }
    .btn-delete:hover { background: #ffe4e6; }

    .count-badge {
        padding: 4px 10px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
    }
</style>

<div class="content-wrapper">
    {{-- Header --}}
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em;">Data Kategori</h2>
            <p style="color: #64748b; font-size: 1rem; margin-top: 6px;">Kelompokkan alat untuk memudahkan manajemen inventaris</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="btn-add">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Live Search Section --}}
    <div class="filter-section">
        <div class="search-wrapper">
            <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </span>
            <input type="text" id="liveSearch" class="search-input" placeholder="Cari nama kategori secara instan...">
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-container">
        @if($kategori->count() > 0)
            <table class="modern-table" id="kategoriTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">NO</th>
                        <th>NAMA KATEGORI</th>
                        <th style="text-align: center;">JUMLAH ALAT</th>
                        <th>TANGGAL DIBUAT</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $item)
                        <tr class="kategori-row">
                            <td class="row-number" style="font-weight: 600; color: #94a3b8;">{{ $loop->iteration }}</td>
                            <td>
                                <div class="kategori-nama" style="font-weight: 600; color: #1e293b;">{{ $item->nama_kategori }}</div>
                            </td>
                            <td style="text-align: center;">
                                <span class="count-badge">
                                    {{ $item->alats()->count() }} Alat
                                </span>
                            </td>
                            <td style="color: #64748b;">{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('admin.kategori.edit', $item->id) }}" class="btn-icon btn-edit" title="Edit Kategori">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Hapus Kategori">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="paginationContainer" style="padding: 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                {{ $kategori->links() }}
            </div>
        @else
            <div style="padding: 60px; text-align: center; color: #94a3b8;">
                <p>Tidak ada data kategori.</p>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearch');
        const rows = document.querySelectorAll('.kategori-row');
        const pagination = document.getElementById('paginationContainer');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const nama = row.querySelector('.kategori-nama').textContent.toLowerCase();
                
                if (nama.includes(query)) {
                    row.style.display = "";
                    visibleCount++;
                    // Update nomor urut secara dinamis
                    row.querySelector('.row-number').textContent = visibleCount;
                } else {
                    row.style.display = "none";
                }
            });

            // Sembunyikan pagination saat mencari agar nomor urut tidak kacau
            if (query.length > 0) {
                pagination.style.opacity = "0.3";
                pagination.style.pointerEvents = "none";
            } else {
                pagination.style.opacity = "1";
                pagination.style.pointerEvents = "auto";
                
                // Reset nomor urut ke aslinya (berdasarkan index loop Laravel) jika pencarian kosong
                let originalCount = 1;
                rows.forEach(row => {
                    row.style.display = "";
                    row.querySelector('.row-number').textContent = originalCount++;
                });
            }
        });
    });
</script>

@endsection