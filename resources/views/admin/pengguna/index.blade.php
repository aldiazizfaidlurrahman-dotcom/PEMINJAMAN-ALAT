@extends('admin.layout')

@section('title', 'Data Pengguna')

@section('content')

<style>
    /* 1. Global & Layout Refinement */
    .content-wrapper { 
        padding: 2.5rem; 
        font-family: 'Inter', -apple-system, sans-serif; 
        background-color: #f8fafc;
        min-height: 100vh;
    }

    /* 2. Enhanced Card Styling */
    .card-container { 
        background: white; 
        border-radius: 16px; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    /* 3. Modern Table Styling */
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

    /* 4. Refined Badges */
    .badge { 
        padding: 6px 12px; 
        border-radius: 9999px; 
        font-size: 0.75rem; 
        font-weight: 600; 
        display: inline-flex;
        align-items: center;
    }
    .badge-role-admin { background: #e0e7ff; color: #4338ca; }
    .badge-role-petugas { background: #fef3c7; color: #92400e; }
    .badge-role-peminjam { background: #dcfce7; color: #166534; }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }
    .dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-active { background: #22c55e; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }
    .dot-inactive { background: #94a3b8; }

    /* 5. Improved Search & Filter Bar */
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
    .filter-select { 
        padding: 12px 16px; 
        border: 1px solid #cbd5e1; 
        border-radius: 10px; 
        background: white;
        min-width: 180px;
        font-size: 0.875rem;
        cursor: pointer;
    }

    /* 6. Action Buttons */
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
        border: 1px solid transparent;
    }
    .btn-edit { background: #f1f5f9; color: #475569; }
    .btn-edit:hover { background: #e2e8f0; color: #1e293b; }
    .btn-delete { background: #fff1f2; color: #e11d48; border: none; cursor: pointer; }
    .btn-delete:hover { background: #ffe4e6; }
</style>

<div class="content-wrapper">
    {{-- Header Section --}}
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.875rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.025em;">Data Pengguna</h2>
            <p style="color: #64748b; font-size: 1rem; margin-top: 6px;">Manajemen akun dan kontrol hak akses sistem</p>
        </div>
        <a href="{{ route('admin.pengguna.create') }}" class="btn-add">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-section">
        <div class="search-wrapper">
            <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </span>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari nama atau username pengguna...">
        </div>
        <select id="roleFilter" class="filter-select">
            <option value="">Semua Hak Akses</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
            <option value="peminjam">Peminjam</option>
        </select>
    </div>

    {{-- Table Card --}}
    <div class="card-container">
        @if($pengguna->count() > 0)
            <table class="modern-table" id="userTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">NO</th>
                        <th>NAMA LENGKAP</th>
                        <th>USERNAME</th>
                        <th>HAK AKSES</th>
                        <th>STATUS AKUN</th>
                        <th style="text-align: right;">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengguna as $item)
                        <tr class="user-row">
                            <td class="row-number" style="font-weight: 600; color: #94a3b8;">{{ $loop->iteration }}</td>
                            <td>
                                <div style="font-weight: 600; color: #1e293b;" class="user-nama">{{ $item->nama }}</div>
                            </td>
                            <td class="user-username" style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: #64748b;">
                                {{ $item->username }}
                            </td>
                            <td>
                                <span class="badge badge-role-{{ $item->role }} user-role">
                                    {{ ucfirst($item->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="status-indicator">
                                    <span class="dot {{ $item->status ? 'dot-active' : 'dot-inactive' }}"></span>
                                    <span style="font-size: 0.875rem; color: {{ $item->status ? '#16a34a' : '#64748b' }}">
                                        {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('admin.pengguna.edit', $item->id) }}" class="btn-icon btn-edit" title="Edit Data">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.pengguna.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Hapus Pengguna">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                {{ $pengguna->links() }}
            </div>
        @else
            <div style="padding: 60px; text-align: center; color: #94a3b8;">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 16px; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <p style="font-size: 1rem; font-weight: 500;">Belum ada data pengguna ditemukan.</p>
            </div>
        @endif
    </div>
</div>

{{-- Script Live Search & Filter --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const tableRows = document.querySelectorAll('.user-row');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value.toLowerCase();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const nama = row.querySelector('.user-nama').textContent.toLowerCase();
                const username = row.querySelector('.user-username').textContent.toLowerCase();
                const role = row.querySelector('.user-role').textContent.toLowerCase();

                const matchesSearch = nama.includes(searchTerm) || username.includes(searchTerm);
                const matchesRole = selectedRole === "" || role.includes(selectedRole);

                if (matchesSearch && matchesRole) {
                    row.style.display = "";
                    visibleCount++;
                    row.querySelector('.row-number').textContent = visibleCount;
                } else {
                    row.style.display = "none";
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
    });
</script>

@endsection