<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'master_hak_akses';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_jabatan',
        'nama_jabatan',
        'id_menu',
        'nama_menu',
        'created_at',
        'updated_at',
    ];
}
