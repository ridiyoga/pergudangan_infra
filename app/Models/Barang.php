<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'kode_barang',
        'nama_barang',
        'satuan',
        'id_kategori',
        'nama_kategori',
        'id_karyawan',
        'nama_karyawan',
        'id_proyek',
        'nama_proyek',
        'created_at',
        'updated_at',
    ];
}
