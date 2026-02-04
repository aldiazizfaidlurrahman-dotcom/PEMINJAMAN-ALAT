<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $peminjamanMenunggu    = Peminjaman::where('status', 'menunggu')->count();
        $peminjamanDisetujui   = Peminjaman::where('status', 'disetujui')->count();
        $peminjamanDitolak     = Peminjaman::where('status', 'ditolak')->count();
        $peminjamanDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();

        return view('petugas.dashboard', compact(
            'peminjamanMenunggu',
            'peminjamanDisetujui',
            'peminjamanDitolak',
            'peminjamanDikembalikan'
        ));
    }

    public function indexPengembalian(Request $request)
    {
        // Ambil semua peminjaman yang statusnya 'disetujui' dan belum dikembalikan
        $peminjaman = \App\Models\Peminjaman::with(['pengguna', 'alat.kategori'])
            ->where('status', 'disetujui')
            ->orderBy('tanggal_kembali', 'asc')
            ->paginate(10);

        return view('petugas.pengembalian.index', compact('peminjaman'));
    }

    public function indexPeminjaman(Request $request)
    {
        $status = $request->input('status', 'menunggu');
        $validStatuses = ['menunggu', 'disetujui', 'ditolak', 'dikembalikan'];
        if (!in_array($status, $validStatuses)) $status = 'menunggu';

        $peminjaman = \App\Models\Peminjaman::with(['pengguna', 'alat.kategori'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas.peminjaman.index', compact('peminjaman', 'status', 'validStatuses'));
    }

    public function indexPeminjamanMenunggu()
    {
        $peminjaman = \App\Models\Peminjaman::with(['pengguna', 'alat.kategori'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('petugas.peminjaman.menunggu', compact('peminjaman'));
    }

    public function showApprovalForm($id)
    {
        $peminjaman = \App\Models\Peminjaman::with(['pengguna', 'alat.kategori'])->findOrFail($id);
        return view('petugas.peminjaman.approval', compact('peminjaman'));
    }

    public function showPengembalianForm($id)
    {
        $peminjaman = \App\Models\Peminjaman::with(['pengguna', 'alat.kategori'])->findOrFail($id);
        $keterlambatan = $peminjaman->calculateKeterlambatan();
        $denda = $keterlambatan * 1000; // contoh denda
        return view('petugas.pengembalian.form', compact('peminjaman', 'keterlambatan', 'denda'));
    }
    public function approve(Request $request, Peminjaman $peminjaman)
    {
        try {
            DB::beginTransaction();

            $peminjaman->update(['status' => 'disetujui']);
            $alat = $peminjaman->alat;
            if ($alat->stok < 1) {
                DB::rollBack();
                return back()->with('error', 'Stok alat tidak mencukupi.');
            }
            $alat->decrement('stok');
            DB::commit();

            // Log aktivitas approve peminjaman
            LogHelper::log('edit', 'Petugas menyetujui peminjaman alat: ' . $alat->nama_alat . ' oleh ' . $peminjaman->pengguna->nama);

            return redirect()->route('petugas.peminjaman.menunggu')
                ->with('success', 'Peminjaman berhasil disetujui. Stok alat berkurang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        try {
            DB::beginTransaction();

            $peminjaman->update([
                'status' => 'ditolak',
                'catatan' => $request->alasan,
            ]);
            DB::commit();

            // Log aktivitas reject peminjaman
            LogHelper::log('edit', 'Petugas menolak peminjaman alat: ' . $peminjaman->alat->nama_alat . ' oleh ' . $peminjaman->pengguna->nama . '. Alasan: ' . $request->alasan);

            return redirect()->route('petugas.peminjaman.menunggu')
                ->with('success', 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function processPengembalian(Request $request, Peminjaman $peminjaman)
    {
        try {
            DB::beginTransaction();

            // Contoh update logic pengembalian
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
            ]);
            $alat = $peminjaman->alat;
            $alat->increment('stok');

            // Hitung denda jika ada
            $keterlambatan = 0;
            $denda = 0;
            if (isset($peminjaman->tanggal_kembali) && isset($peminjaman->tanggal_kembali_seharusnya)) {
                $keterlambatan = max(0, now()->diffInDays($peminjaman->tanggal_kembali_seharusnya, false));
                $denda = $keterlambatan * 1000; // contoh denda
            }

            DB::commit();

            // Log aktivitas pengembalian alat
            LogHelper::log('kembali', 'Petugas mengonfirmasi pengembalian alat: ' . $alat->nama_alat . ' oleh ' . $peminjaman->pengguna->nama);

            $message = 'Pengembalian berhasil dicatat. ';
            if ($keterlambatan > 0) {
                $message .= "Denda keterlambatan: Rp " . number_format($denda, 0, ',', '.');
            }

            return redirect()->route('petugas.pengembalian.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
