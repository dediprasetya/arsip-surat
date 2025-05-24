<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;

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

}
