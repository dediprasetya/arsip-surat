<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat; // Surat masuk
use App\Models\SuratKeluar;
use App\Models\User;
use App\Models\Bidang;
use App\Models\TimKerja;
use App\Models\KlasifikasiSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'staff') {
                return redirect()->route('staff.dashboard');
            }
        }
        return redirect('/login');
    }

    public function adminDashboard()
    {
        $totalSuratMasuk = Surat::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalUser = User::count();
        $totalBidang = Bidang::count();
        $totalTimKerja = TimKerja::count();
        $totalKlasifikasi = KlasifikasiSurat::count();

        // Data grafik surat masuk per bulan
        $suratMasukChart = Surat::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // Data grafik surat keluar per bulan
        $suratKeluarChart = SuratKeluar::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        return view('admin.dashboard', [
            'user' => Auth::user(),
            'totalSuratMasuk' => $totalSuratMasuk,
            'totalSuratKeluar' => $totalSuratKeluar,
            'totalUser' => $totalUser,
            'totalBidang' => $totalBidang,
            'totalTimKerja' => $totalTimKerja,
            'totalKlasifikasi' => $totalKlasifikasi,
            'suratMasukChart' => $suratMasukChart,
            'suratKeluarChart' => $suratKeluarChart
        ]);
    }
}

