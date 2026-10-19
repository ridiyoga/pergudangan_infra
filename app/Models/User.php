<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'master_user';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_karyawan',
        'nama_karyawan',
        'username',
        'password',
        'is_active',
        'id_proyek',
        'nama_proyek',
        'created_at',
        'updated_at',
    ];
}
