<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Nama tabel di database
    protected $primaryKey = 'id'; // Primary key
    public $incrementing = true; // Auto Increment
    protected $keyType = 'int'; // Tipe data primary key
    public $timestamps = true; // Gunakan timestamps (created_at, updated_at)

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'tim_kerja_id',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'string', // Laravel 10+ mendukung hashing otomatis
        'role' => 'string',
    ];

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id');
    }
}
