<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_karyawan',
        'no_ktp',
        'no_wa',
        'email',
        'id_jabatan',
        'nama_jabatan',
        'is_active',
        'id_area',
        'nama_area',
        'created_at',
        'updated_at',
    ];
}
