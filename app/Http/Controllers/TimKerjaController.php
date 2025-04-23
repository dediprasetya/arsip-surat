<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimKerja;
use App\Models\Bidang;

class TimKerjaController extends Controller
{
    public function index()
    {
        $timKerja = TimKerja::with('bidang')->get();
        $bidang = Bidang::all();
        return view('klasifikasi.index', compact('timKerja', 'bidang')); // Perbaikan variabel
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tim_kerja' => 'required|string|unique:tim_kerjas,nama_tim_kerja',
            'bidang_id' => 'required|exists:bidangs,id',
        ]);

        TimKerja::create($request->only(['nama_tim_kerja', 'bidang_id'])); // Perbaikan penggunaan mass assignment

        return redirect()->route('klasifikasi.index')->with('success', 'Tim Kerja berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $timKerja = TimKerja::findOrFail($id);
        $bidang = Bidang::all();
        return view('klasifikasi.edit', compact('timKerja', 'bidang')); // Perbaikan variabel
    }

    public function update(Request $request, $id)
    {
        $timKerja = TimKerja::findOrFail($id);

        $request->validate([
            'nama_tim_kerja' => 'required|string|unique:tim_kerjas,nama_tim_kerja,' . $id,
            'bidang_id' => 'required|exists:bidangs,id',
        ]);

        $timKerja->update($request->only(['nama_tim_kerja', 'bidang_id'])); // Perbaikan penggunaan mass assignment

        return redirect()->route('klasifikasi.index')->with('success', 'Tim Kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $timKerja = TimKerja::findOrFail($id);
        $timKerja->delete();

        return redirect()->route('klasifikasi.index')->with('success', 'Tim Kerja berhasil dihapus.');
    }

    public function getByBidang($bidang_id)
    {
        $timKerja = TimKerja::where('bidang_id', $bidang_id)->get();
        return response()->json($timKerja);
    }
}
