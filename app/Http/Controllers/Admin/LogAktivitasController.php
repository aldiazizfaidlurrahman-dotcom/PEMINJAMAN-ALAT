<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::query();

        // Filter: search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nama', 'like', "%$q%")
                    ->orWhere('role', 'like', "%$q%")
                    ->orWhere('jenis', 'like', "%$q%")
                    ->orWhere('keterangan', 'like', "%$q%")
                    ;
            });
        }
        // Filter: role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        // Filter: jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        // Filter: tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logAktivitas = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('admin.logAktivitas', compact('logAktivitas'));
    }
}
