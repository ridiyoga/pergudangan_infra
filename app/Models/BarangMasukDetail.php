<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangMasukDetail extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_barang_masuk',

        'no_bapb',

        'tanggal_masuk',
        'surat_jalan',
        'supplier',
        'id_barang',
        'nama_barang',

        'jumlah',
        'satuan',

        'keterangan',

        'id_karyawan',
        'nama_karyawan',

        'created_at',
        'updated_at',
    ];
}
