<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\User;
use App\Models\KlasifikasiSurat;
use App\Models\Bidang;
use App\Models\TimKerja;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Exports\SuratMasukExport;
use Maatwebsite\Excel\Facades\Excel;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $klasifikasi = KlasifikasiSurat::all();
        $users = User::all();
        $bidang = Bidang::all();
        $timkerja = TimKerja::all();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session habis, silakan login kembali.');
        }

        // Query dasar
        $query = Surat::with('klasifikasi.timKerja.bidang');

        // Filter role
        if ($user->role !== 'admin') {
            $query->where('tujuan_disposisi', $user->id);
        }

        // Filter berdasarkan form pencarian
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

        if ($request->filled('tujuan_disposisi')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->tujuan_disposisi . '%');
            })->orWhere('tujuan_disposisi', 'like', '%' . $request->tujuan_disposisi . '%');
        }

        if ($request->filled('status_surat')) {
            $query->where('status_surat', $request->status_surat);
        }

        $surat = $query->paginate(10)->withQueryString();

        return view('surat.index', compact('surat', 'klasifikasi', 'users', 'bidang', 'timkerja'))
            ->with('nomor_surat', $request->nomor_surat)
            ->with('asal_surat', $request->asal_surat)
            ->with('perihal', $request->perihal)
            ->with('tanggal_surat', $request->tanggal_surat)
            ->with('nomor_agenda_umum', $request->nomor_agenda_umum)
            ->with('klasifikasi_filter', $request->klasifikasi)
            ->with('tujuan_disposisi_filter', $request->tujuan_disposisi)
            ->with('status_surat', $request->status_surat);
    }




    public function create()
    {
        $users = User::all();
        $klasifikasi = KlasifikasiSurat::all();
        $bidang = Bidang::all();
        $timkerja = TimKerja::all();

        return view('surat.create', compact('users', 'klasifikasi', 'bidang', 'timkerja'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'klasifikasi_id' => 'required|exists:klasifikasi_surat,id',
            'nomor_surat' => 'required|string|unique:surat,nomor_surat',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string',
            'tanggal_penerimaan_surat' => 'nullable|date',
            'tanggal_disposisi' => 'nullable|date',
            'isi_surat' => 'required|string',
            'asal_surat' => 'nullable|string',
            'status_surat' => 'required|in:belum diterima,sudah diterima,sudah ditindaklanjuti',
            'tujuan_disposisi' => 'nullable|exists:users,id',
            'isi_disposisi' => 'nullable|string',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nomor_agenda_umum' => 'required|string|unique:surat,nomor_agenda_umum',
        ]);

        // Menyimpan file jika diunggah
        $fileName = null;
        if ($request->hasFile('file_surat')) {
            $fileName = $request->file('file_surat')->store('uploads', 'public');
        }

        // Simpan data surat
        $surat = Surat::create([
            'klasifikasi_id' => $request->klasifikasi_id,
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'perihal' => $request->perihal,
            'tanggal_penerimaan_surat' => $request->tanggal_penerimaan_surat,
            'tanggal_disposisi' => $request->tanggal_disposisi,
            'isi_surat' => $request->isi_surat,
            'asal_surat' => $request->asal_surat,
            'status_surat' => $request->status_surat,
            'tujuan_disposisi' => $request->tujuan_disposisi,
            'isi_disposisi' => $request->isi_disposisi,
            'file_surat' => $fileName,
            'nomor_agenda_umum' => $request->nomor_agenda_umum,
        ]);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil ditambahkan.');
    }

    public function show(Surat $surat)
    {
        return view('surat.show', compact('surat'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $surat = Surat::with(['user', 'klasifikasi.timKerja.bidang'])->findOrFail($id);

        // **Ambil daftar klasifikasi surat**
        $klasifikasi = Klasifikasi::with(['timKerja.bidang'])->get();
        $users = User::all();

        // **Cek izin untuk staff (hanya bisa edit surat tujuan disposisinya)**
        if ($user->role == 'staff' && $surat->tujuan_disposisi != $user->id) {
            return redirect()->route('surat.index')->with('error', 'Anda tidak memiliki izin untuk mengedit surat ini.');
        }

        return view('surat.edit', compact('surat', 'klasifikasi', 'users'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $surat = Surat::findOrFail($id);

        // **Cek izin staff: hanya bisa edit surat tujuan disposisinya**
        if ($user->role == 'staff' && $surat->tujuan_disposisi != $user->id) {
            return redirect()->route('surat.index')->with('error', 'Anda tidak memiliki izin untuk mengedit surat ini.');
        }

        // **Validasi input**
        $request->validate([
            'klasifikasi_id' => 'required|exists:klasifikasi_surat,id',
            'nomor_surat' => 'required|string|unique:surat,nomor_surat,' . $surat->id,
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string',
            'tanggal_penerimaan_surat' => 'nullable|date',
            'tanggal_disposisi' => 'nullable|date',
            'isi_surat' => 'required|string',
            'asal_surat' => 'nullable|string',
            'status_surat' => 'required|in:belum diterima,sudah diterima,sudah ditindaklanjuti',
            'tujuan_disposisi' => 'nullable|exists:users,id',
            'isi_disposisi' => 'nullable|string',
            'file_surat' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'nomor_agenda_umum' => 'required|string|unique:surat,nomor_agenda_umum,' . $surat->id,
        ]);

        // **Jika ada file baru, hapus file lama dan simpan file baru**
        if ($request->hasFile('file_surat')) {
            if ($surat->file_surat) {
                Storage::disk('public')->delete($surat->file_surat);
            }
            $surat->file_surat = $request->file('file_surat')->store('uploads', 'public');
        }

        // **Update data surat**
        $surat->update([
            'klasifikasi_id' => $request->klasifikasi_id,
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'perihal' => $request->perihal,
            'tanggal_penerimaan_surat' => $request->tanggal_penerimaan_surat,
            'tanggal_disposisi' => $request->tanggal_disposisi,
            'isi_surat' => $request->isi_surat,
            'asal_surat' => $request->asal_surat,
            'status_surat' => $request->status_surat,
            'tujuan_disposisi' => $request->tujuan_disposisi,
            'isi_disposisi' => $request->isi_disposisi,
            'nomor_agenda_umum' => $request->nomor_agenda_umum,
        ]);

        return redirect()->route('surat.index')->with('success', 'Surat berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->file_surat) {
            Storage::disk('public')->delete($surat->file_surat);
        }

        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    public function terimaSurat($id)
    {
        $surat = Surat::findOrFail($id);

        // Jika surat sudah diterima sebelumnya, tidak perlu mengubah status atau tanggal
        if ($surat->status_surat === 'sudah diterima' || $surat->status_surat === 'sudah ditindaklanjuti') {
            return response()->json(['success' => false, 'message' => 'Surat sudah pernah diterima sebelumnya.']);
        }

        // Jika surat belum pernah diterima, ubah status dan simpan tanggal penerimaan
        $surat->status_surat = 'sudah diterima';
        $surat->tanggal_diterima_staf = now();
        $surat->save();

        return response()->json(['success' => true, 'message' => 'Surat telah diterima.']);
    }

    public function tindaklanjutiSurat($id)
    {
        $surat = Surat::findOrFail($id);
        if ($surat->status_surat !== 'sudah diterima') {
            return response()->json(['success' => false, 'message' => 'Surat harus diterima terlebih dahulu!'], 400);
        }

        $surat->status_surat = 'sudah ditindaklanjuti';
        $surat->tanggal_ditindaklanjuti_staf = now();
        $surat->save();

        return response()->json(['success' => true, 'message' => 'Surat telah ditindaklanjuti.']);
    }

    public function getUsersByKlasifikasi($id)
    {
        $klasifikasi = KlasifikasiSurat::with('timKerja.users')->find($id);

        if (!$klasifikasi || !$klasifikasi->timKerja) {
            return response()->json([]);
        }

        $users = $klasifikasi->timKerja->users;

        return response()->json($users);
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();

        $query = \App\Models\Surat::with('klasifikasi.timKerja.bidang', 'user');

        // Filter berdasarkan role
        if ($user->role !== 'admin') {
            $query->where('tujuan_disposisi', $user->id);
        }

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

        if ($request->filled('tujuan_disposisi')) {
            $query->where('tujuan_disposisi', $request->tujuan_disposisi);
        }

        if ($request->filled('status_surat')) {
            $query->where('status_surat', $request->status_surat);
        }

        $data = $query->get();

        return Excel::download(new \App\Exports\SuratMasukExport($data), 'surat-masuk.xlsx');
    }




}
