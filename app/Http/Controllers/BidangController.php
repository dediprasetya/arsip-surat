<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;

class BidangController extends Controller
{
    public function index()
    {
        $bidang = Bidang::all();
        return view('klasifikasi.index', compact('bidang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|unique:bidangs,nama_bidang',
        ]);

        Bidang::create($request->all());

        return redirect()->route('klasifikasi.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $bidang = Bidang::findOrFail($id);
        return view('klasifikasi.edit', compact('bidang'));
    }

    public function update(Request $request, $id)
    {
        $bidang = Bidang::findOrFail($id);

        $request->validate([
            'nama_bidang' => 'required|string|unique:bidangs,nama_bidang,' . $id,
        ]);

        $bidang->update($request->all());

        return redirect()->route('klasifikasi.index')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return redirect()->route('klasifikasi.index')->with('success', 'Bidang berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $bidang = Bidang::where('nama_bidang', 'LIKE', "%$search%")
                        ->limit(10)
                        ->get();

        return response()->json($bidang->map(function($item) {
            return ['id' => $item->id, 'text' => $item->nama_bidang];
        }));
    }
}
