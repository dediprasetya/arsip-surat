<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\KlasifikasiSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratKeluarExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use PDF;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::with(['klasifikasi.timKerja.bidang', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                    ->orWhere('tanggal_surat', 'like', "%$search%")
                    ->orWhere('perihal', 'like', "%$search%")
                    ->orWhere('tujuan_surat', 'like', "%$search%")
                    ->orWhere('isi_surat', 'like', "%$search%")
                    ->orWhere('catatan', 'like', "%$search%")
                    ->orWhereHas('klasifikasi', function ($q2) use ($search) {
                        $q2->where('nama_klasifikasi', 'like', "%$search%");
                    })
                    ->orWhereHas('user', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%$search%");
                    });
            });
        }

        $suratKeluar = $query->orderBy('tanggal_surat', 'desc')->paginate(10);
        $klasifikasi = KlasifikasiSurat::with('timKerja.bidang')->get();

        return view('surat-keluar.index', compact('suratKeluar', 'klasifikasi'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'klasifikasi_id' => 'required',
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required',
            'tujuan_surat' => 'required',
            'isi_surat' => 'required',
            'catatan' => 'nullable',
            'file_surat' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/surat_keluar', $namaFile);

            // Simpan hanya nama file
            $validated['file_surat'] = $namaFile;
        }

        $validated['user_id'] = Auth::id();

        SuratKeluar::create($validated);

        return back()->with('success', 'Surat berhasil ditambahkan');
    }


    
    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $validated = $request->validate([
            'klasifikasi_id' => 'required',
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required',
            'tujuan_surat' => 'required',
            'isi_surat' => 'required',
            'catatan' => 'nullable',
            'file_surat' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_surat')) {
            // Hapus file lama (jika ada)
            if ($suratKeluar->file_surat && Storage::exists('public/surat_keluar/' . $suratKeluar->file_surat)) {
                Storage::delete('public/surat_keluar/' . $suratKeluar->file_surat);
            }

            $file = $request->file('file_surat');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/surat_keluar', $namaFile);

            // Simpan hanya nama file
            $validated['file_surat'] = $namaFile;
        }

        $suratKeluar->update($validated);

        return back()->with('success', 'Surat berhasil diperbarui');
    }


    public function destroy(SuratKeluar $suratKeluar)
    {
        // Hapus file dari storage jika ada
        if ($suratKeluar->file_surat && Storage::exists('public/surat_keluar/' . $suratKeluar->file_surat)) {
            Storage::delete('public/surat_keluar/' . $suratKeluar->file_surat);
        }

        // Hapus data dari database
        $suratKeluar->delete();

        return back()->with('success', 'Surat berhasil dihapus');
    }


    public function exportPdf(Request $request)
    {
        $query = SuratKeluar::with('klasifikasi.timKerja.bidang','user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                ->orWhere('tanggal_surat', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%")
                ->orWhere('tujuan_surat', 'like', "%{$search}%")
                ->orWhere('isi_surat', 'like', "%{$search}%")
                ->orWhere('catatan', 'like', "%{$search}%")
                ->orWhereHas('klasifikasi', function ($q2) use ($search) {
                    $q2->where('nama_klasifikasi', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($q3) use ($search) {
                    $q3->where('name', 'like', "%{$search}%");
                });
            });
        }

        $suratKeluar = $query->get();

        $pdf = Pdf::loadView('surat-keluar.cetak-pdf', compact('suratKeluar'));

        return $pdf->download('surat_keluar.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = SuratKeluar::with(['klasifikasi.timKerja.bidang', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('tanggal_surat', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%")
                    ->orWhere('tujuan_surat', 'like', "%{$search}%")
                    ->orWhere('isi_surat', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhereHas('klasifikasi', function ($q2) use ($search) {
                        $q2->where('nama_klasifikasi', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $data = $query->get();

        return Excel::download(new SuratKeluarExport($data), 'surat_keluar.xlsx');
    }
    



}

