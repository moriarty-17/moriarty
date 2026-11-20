<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran'; 
    public $timestamps = false; 

    protected $fillable = [
        'kode_pembayaran',
        'kode_penawaran',
        'harga',
        'metode_pembayaran',
        'status',
        'waktu_pembayaran',
        'snap_token',  
        'paypal_link'
    ];

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'kode_penawaran', 'kode_penawaran');
    }

    public function pemesanan()
    {
        // Ubah dari 'id_pemesanan' menjadi 'kode_penawaran'
        return $this->belongsTo(Pemesanan::class, 'kode_penawaran', 'kode_penawaran');
    }
}
