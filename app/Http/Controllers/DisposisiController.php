<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DisposisiController extends Controller
{
    public function disposisiOlehKepala(Request $request)
    {
        $request->validate([
            'surat_id' => 'required|exists:surat,id',
            'tujuan_disposisi' => 'required|exists:users,id',
            'isi_disposisi' => 'required|string',
        ]);

        $surat = Surat::findOrFail($request->surat_id);
        //$surat->disposisi_oleh = Auth::id(); // ID kepala bidang
        $surat->tujuan_disposisi = $request->tujuan_disposisi;
        $surat->isi_disposisi = $request->isi_disposisi;
        $surat->status_disposisi = 'sudah';
        $surat->tanggal_disposisi = now(); // Otomatis isi tanggal disposisi
        $surat->save();

        return redirect()->route('kepala.dashboard')->with('success', 'Surat berhasil didisposisikan.');
    }

    public function suratMasuk()
    {
        $suratMasuk = Surat::with('klasifikasi.timKerja')
            ->whereNull('status_disposisi') // atau kondisi sesuai kebutuhanmu
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        return view('kepala.surat-masuk', compact('suratMasuk'));
    }

    public function suratKeluar()
    {
        $suratKeluar = SuratKeluar::with('klasifikasi.timKerja')
            ->where('status', 'menunggu')
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        return view('kepala.surat-keluar', compact('suratKeluar'));
    }

    public function setujuiSuratKeluar($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $surat->status = 'disetujui';
        $surat->alasan_penolakan = null;
        $surat->save();

        return back()->with('success', 'Surat keluar berhasil disetujui.');
    }

    public function tolakSuratKeluar(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $surat = SuratKeluar::findOrFail($id);
        $surat->status = 'ditolak';
        $surat->alasan_penolakan = $request->alasan_penolakan;
        $surat->save();

        return back()->with('error', 'Surat keluar ditolak dengan alasan: ' . $request->alasan_penolakan);
    }

    public function kirimUlang($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $surat->status = 'menunggu';
        $surat->alasan_penolakan = null;
        $surat->save();

        return back()->with('success', 'Surat berhasil dikirim ulang.');
    }

    public function dashboard()
    {
        $jumlahSuratMasuk = Surat::count();
        $jumlahSuratKeluar = SuratKeluar::count();
        // Tambahkan statistik lain jika perlu

        return view('kepala.dashboard', compact('jumlahSuratMasuk', 'jumlahSuratKeluar'));
    }
    public function disposisiSurat()
    {
        $user = Auth::user();

        // Ambil semua surat masuk yang belum didisposisi oleh kepala bidang
        $suratMasuk = \App\Models\Surat::with('klasifikasi.timKerja')
                        ->where('status_disposisi', 'belum')
                        ->orderBy('tanggal_surat', 'desc')
                        ->get();
        return view('kepala.disposisi_surat', compact('user','suratMasuk'));
    }

    public function semuaSuratMasuk()
    {
        $surat_masuk = Surat::latest()->get();
        return view('kepala.surat_masuk', compact('surat_masuk'));
    }

    public function semuaSuratKeluar()
    {
        $surat_keluar = SuratKeluar::latest()->get();
        return view('kepala.surat_keluar', compact('surat_keluar'));
    }



}
