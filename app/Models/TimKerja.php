<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    use HasFactory;

    protected $table = 'tim_kerjas';

    protected $fillable = ['nama_tim_kerja', 'bidang_id'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class,'bidang_id');
    }

    public function klasifikasiSurat()
    {
        return $this->hasMany(KlasifikasiSurat::class, 'tim_kerja_id');
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
