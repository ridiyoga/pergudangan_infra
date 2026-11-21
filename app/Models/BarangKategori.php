<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangKategori extends Model
{
    protected $table = 'barang_kategori';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_kategori',
        'id_karyawan',
        'nama_karyawan',
        'id_proyek',
        'nama_proyek',
        'created_at',
        'updated_at',
    ];
}
