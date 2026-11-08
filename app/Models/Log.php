<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'master_log';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'id_karyawan',
        'nama_karyawan',
        'aksi',
        'deskripsi',
        'id_menu',
        'nama_menu',
        'id_tabel_baru',
        'nama_tabel',
        'id_tabel_lama',
        'keterangan',
        'created_at',
        'updated_at',
    ];
}
