<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairan extends Model
{
    use HasFactory;
    protected $table='pencairan';
    protected $connection='mysql';
    protected $primaryKey='id_pencairan';
    protected $fillable = array('id_pengguna', 'kode_pencairan', 'jumlah_pencairan', 'metode_pencairan', 'alamat_pencairan', 'status', 'waktu_pencairan');
    public $timestamps=false;

    public function pengguna()
    {
        // Parameter: (ModelTujuan, foreign_key_di_tabel_ini, owner_key_di_tabel_tujuan)
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
