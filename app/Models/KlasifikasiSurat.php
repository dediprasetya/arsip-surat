<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiSurat extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi_surat';
    protected $fillable = ['kode', 'nama_klasifikasi', 'tim_kerja_id'];

    public function surat()
    {
        return $this->hasMany(Surat::class, 'klasifikasi_id');
    }

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id');
    }

    //public function bidang()
    //{
    //    return $this->timKerja->belongsTo(Bidang::class, 'bidang_id');
    //}
}