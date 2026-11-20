<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan'; 
    public $timestamps = false; 

    protected $fillable = [
        'id_user',
        'id_pengguna', // Tambahkan ini agar tidak ditolak Eloquent jika DB memakai id_pengguna
        'kode_penawaran',
        'id_pembayaran',
        'status',
        'waktu_pemesanan'
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'kode_penawaran', 'kode_penawaran');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_pengguna');
    }
}