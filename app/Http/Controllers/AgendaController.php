<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratKeluar;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgendaExport;


class AgendaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan & tahun dari request (default ke bulan & tahun sekarang)
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil surat masuk berdasarkan bulan & tahun
        $suratMasuk = Surat::whereMonth('tanggal_surat', $bulan)
            ->whereYear('tanggal_surat', $tahun)
            ->orderBy('tanggal_surat', 'asc')
            ->get();

        return view('agenda.index', compact('suratMasuk', 'bulan', 'tahun'));
    }


    // Export PDF
    public function exportPDF(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

         // Ambil surat masuk berdasarkan bulan & tahun
         $suratMasuk = Surat::whereMonth('tanggal_surat', $bulan)
         ->whereYear('tanggal_surat', $tahun)
         ->orderBy('tanggal_surat', 'asc')
         ->get();

        $pdf = PDF::loadView('agenda.pdf', compact('suratMasuk', 'bulan', 'tahun'))->setPaper('A4', 'landscape');
        return $pdf->download("Agenda_Surat_Masuk_{$bulan}_{$tahun}.pdf");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        return Excel::download(new AgendaExport($bulan, $tahun), 'agenda-surat.xlsx');
    }

    public function suratKeluar(Request $request)
    {
        $query = \App\Models\SuratKeluar::with('klasifikasi.timKerja.bidang', 'user');

        // Filter bulan dan tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_surat', $request->tahun);
        }

        $data = $query->orderBy('tanggal_surat', 'asc')->get();

        return view('agenda.surat-keluar', compact('data'));
    }

    public function exportSuratKeluarPdf(Request $request)
    {
        $query = \App\Models\SuratKeluar::with('klasifikasi.timKerja.bidang', 'user');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_surat', $request->tahun);
        }

        $data = $query->orderBy('tanggal_surat', 'desc')->get();

        $pdf = PDF::loadView('agenda.surat-keluar-pdf', compact('data'))->setPaper('A4', 'landscape');

        return $pdf->download('agenda_surat_keluar.pdf');
    }

    public function exportSuratKeluarExcel(Request $request)
    {
        return Excel::download(new AgendaSuratKeluarExport($request->bulan, $request->tahun), 'agenda_surat_keluar.xlsx');
    }


}

