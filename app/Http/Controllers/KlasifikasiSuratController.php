<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KlasifikasiSurat;
use App\Models\Bidang;
use App\Models\TimKerja;

class KlasifikasiSuratController extends Controller
{
    public function index()
    {
        $klasifikasi = KlasifikasiSurat::with('timKerja.bidang')->get();
        $bidang = Bidang::all();
        $timKerja = TimKerja::all();

        return view('klasifikasi.index', compact('klasifikasi', 'bidang', 'timKerja'));
    }

    public function create()
    {
        $bidang = Bidang::all();
        return view('klasifikasi.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:klasifikasi_surat,kode',
            'nama_klasifikasi' => 'required|string',
            'tim_kerja_id' => 'required|exists:tim_kerjas,id',
        ]);

        KlasifikasiSurat::create([
            'kode' => $request->kode,
            'nama_klasifikasi' => $request->nama_klasifikasi,
            'tim_kerja_id' => $request->tim_kerja_id,
        ]);

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi surat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $klasifikasi = KlasifikasiSurat::findOrFail($id);
        $bidang = Bidang::all();

        // Ambil bidang berdasarkan tim kerja
        $timKerja = TimKerja::all();

        return view('klasifikasi.edit', compact('klasifikasi', 'bidang', 'timKerja'));
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = KlasifikasiSurat::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|unique:klasifikasi_surat,kode,' . $id,
            'nama_klasifikasi' => 'required|string',
            'tim_kerja_id' => 'required|exists:tim_kerjas,id',
        ]);

        $klasifikasi->update([
            'kode' => $request->kode,
            'nama_klasifikasi' => $request->nama_klasifikasi,
            'tim_kerja_id' => $request->tim_kerja_id,
        ]);

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi surat berhasil diperbarui.');
    }

    public function getByTimKerja($tim_kerja_id)
    {
        $klasifikasi = KlasifikasiSurat::where('tim_kerja_id', $tim_kerja_id)->get();
        return response()->json($klasifikasi);
    }

    public function destroy($id)
    {
        $klasifikasi = KlasifikasiSurat::findOrFail($id);
        $klasifikasi->delete();

        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi surat berhasil dihapus.');
    }
}
