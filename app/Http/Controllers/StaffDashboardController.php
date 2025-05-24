<?php

namespace App\Http\Controllers;
use App\Exports\SuratMasukExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\SuratKeluar; // Model surat untuk mengambil data surat

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Statistik hanya untuk user yang sedang login
        $totalSuratMasuk = Surat::where('tujuan_disposisi', $user->id)->count();
        $totalSuratKeluar = SuratKeluar::where('user_id', $user->id)->count();

        // Mulai query dengan filter user staff (tujuan_disposisi = user id)
        $query = Surat::where('tujuan_disposisi', $user->id)->with('klasifikasi');

        // Tambahkan filter berdasarkan input pencarian
        if ($request->filled('nomor_surat')) {
            $query->where('nomor_surat', 'like', '%' . $request->nomor_surat . '%');
        }
        if ($request->filled('asal_surat')) {
            $query->where('asal_surat', 'like', '%' . $request->asal_surat . '%');
        }
        if ($request->filled('perihal')) {
            $query->where('perihal', 'like', '%' . $request->perihal . '%');
        }
        if ($request->filled('tanggal_surat')) {
            $query->whereDate('tanggal_surat', $request->tanggal_surat);
        }
        if ($request->filled('nomor_agenda_umum')) {
            $query->where('nomor_agenda_umum', 'like', '%' . $request->nomor_agenda_umum . '%');
        }
        if ($request->filled('klasifikasi')) {
            $query->whereHas('klasifikasi', function ($q) use ($request) {
                $q->where('nama_klasifikasi', 'like', '%' . $request->klasifikasi . '%');
            });
        }        
        if ($request->filled('status_surat')) {
            $query->where('status_surat', $request->status_surat);
        }

        // Ambil hasil pencarian dengan pagination
        $surat = $query->paginate(10)->appends($request->query());

        return view('staff.dashboard', compact('surat','totalSuratMasuk', 'totalSuratKeluar'));
    }

    public function exportExcel(Request $request)
    {
        $userId = Auth::id();

        $query = Surat::with('klasifikasi.timKerja.bidang', 'user')
                    ->where('tujuan_disposisi', $userId); // hanya untuk staf login

        // Filter pencarian berdasarkan input user
        if ($request->filled('nomor_surat')) {
            $query->where('nomor_surat', 'like', '%' . $request->nomor_surat . '%');
        }

        if ($request->filled('asal_surat')) {
            $query->where('asal_surat', 'like', '%' . $request->asal_surat . '%');
        }

        if ($request->filled('perihal')) {
            $query->where('perihal', 'like', '%' . $request->perihal . '%');
        }

        if ($request->filled('tanggal_surat')) {
            $query->whereDate('tanggal_surat', $request->tanggal_surat);
        }

        if ($request->filled('nomor_agenda_umum')) {
            $query->where('nomor_agenda_umum', 'like', '%' . $request->nomor_agenda_umum . '%');
        }

        if ($request->filled('klasifikasi_id')) {
            $query->where('klasifikasi_id', $request->klasifikasi_id);
        }

        if ($request->filled('status_surat')) {
            $query->where('status_surat', $request->status_surat);
        }

        // Ambil data
        $data = $query->get();

        // Ekspor ke Excel
        return Excel::download(new SuratMasukExport($data), 'surat-masuk-staff.xlsx');
    }

    public function suratMasuk(Request $request)
    {
        $user = Auth::user();
        // Mulai query dengan filter user staff (tujuan_disposisi = user id)
        $query = Surat::where('tujuan_disposisi', $user->id)->with('klasifikasi');

        // Tambahkan filter berdasarkan input pencarian
        if ($request->filled('nomor_surat')) {
            $query->where('nomor_surat', 'like', '%' . $request->nomor_surat . '%');
        }
        if ($request->filled('asal_surat')) {
            $query->where('asal_surat', 'like', '%' . $request->asal_surat . '%');
        }
        if ($request->filled('perihal')) {
            $query->where('perihal', 'like', '%' . $request->perihal . '%');
        }
        if ($request->filled('tanggal_surat')) {
            $query->whereDate('tanggal_surat', $request->tanggal_surat);
        }
        if ($request->filled('nomor_agenda_umum')) {
            $query->where('nomor_agenda_umum', 'like', '%' . $request->nomor_agenda_umum . '%');
        }
        if ($request->filled('klasifikasi')) {
            $query->whereHas('klasifikasi', function ($q) use ($request) {
                $q->where('nama_klasifikasi', 'like', '%' . $request->klasifikasi . '%');
            });
        }        
        if ($request->filled('status_surat')) {
            $query->where('status_surat', $request->status_surat);
        }

        // Ambil hasil pencarian dengan pagination
        $surat = $query->paginate(10)->appends($request->query());

        return view('staff.surat_masuk', compact('surat'));
    }

}
