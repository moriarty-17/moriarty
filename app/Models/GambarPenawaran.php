<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarPenawaran extends Model
{
    protected $table = 'gambar_penawaran';

    protected $primaryKey = 'id_gambar';

    protected $fillable = [
        'id_penawaran',
        'gambar',
    ];

    public $timestamps = false;

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
    }
}