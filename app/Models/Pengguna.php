<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory;
    protected $table='pengguna';
    protected $connection='mysql';
    protected $primaryKey='id_pengguna';
    protected $fillable = array('email', 'username', 'password', 'role', 'gambar', 'saldo', 'identitas', 'status');
    public $timestamps=false;

    public function Penawaran()
    {
        return $this->hasMany('App\Models\Penawaran', 'id_pengguna');
    }
}
