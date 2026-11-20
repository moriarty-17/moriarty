<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GambarPenawaran;

class Penawaran extends Model
{
    use HasFactory;
    protected $table = 'penawaran';
    protected $connection = 'mysql';
    protected $primaryKey = 'id_penawaran';
    protected $fillable = array('id_pengguna', 'jenis', 'kode_penawaran', 'judul', 'gambar', 'deskripsi', 'informasi_penawaran', 'harga', 'status', 'created_at', 'updated_at');
    public $timestamps = false;

    // Perjelas foreign key dan owner key agar akurat
    public function Pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function gambarPenawaran()
    {
        return $this->hasMany(
            GambarPenawaran::class,
            'id_penawaran',
            'id_penawaran'
        );
    }
}