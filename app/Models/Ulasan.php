<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';
    
    protected $fillable = [
        'id_pemesanan',
        'id_pembeli',
        'id_penjual',
        'rating',
        'komentar',
    ];

    // Relasi ke Pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    // Relasi ke User (Pembeli)
    public function pembeli()
    {
        return $this->belongsTo(Pengguna::class, 'id_pembeli', 'id_pengguna'); // Sesuaikan 'id' dengan primary key tabel users Anda
    }

    // Relasi ke User (Penjual)
    public function penjual()
    {
        return $this->belongsTo(Pengguna::class, 'id_penjual', 'id_pengguna');
    }
}
