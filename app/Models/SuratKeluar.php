<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $fillable = [
        'klasifikasi_id', 'nomor_surat', 'tanggal_surat', 'perihal',
        'tujuan_surat', 'isi_surat', 'catatan', 'file_surat', 'user_id'
    ];

    public function klasifikasi()
    {
        return $this->belongsTo(KlasifikasiSurat::class, 'klasifikasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
