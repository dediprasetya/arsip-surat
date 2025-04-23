<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';
    protected $primaryKey = 'id'; // Sesuai dengan nama primary key

    protected $fillable = [
        'klasifikasi_id',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'tanggal_penerimaan_surat', // Ditambahkan
        'tanggal_diterima_staf', // Ditambahkan
        'tanggal_ditindaklanjuti_staf', // Ditambahkan
        'tanggal_disposisi', // Ditambahkan
        'isi_surat',
        'asal_surat',
        'status_surat',
        'tujuan_disposisi',
        'isi_disposisi',
        'file_surat',
        'bidang_id',
        'tim_kerja_id',
        'nomor_agenda_umum',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'tujuan_disposisi');
    }

    public function klasifikasi()
    {
        return $this->belongsTo(KlasifikasiSurat::class, 'klasifikasi_id');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class,'bidang_id');
    }

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class,'tim_kerja_id');
    }
}
