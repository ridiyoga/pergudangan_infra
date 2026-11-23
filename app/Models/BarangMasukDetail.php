<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangMasukDetail extends Model
{
    protected $table = 'barang_masuk_detail';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_barang_masuk',
        'kode_barang',

        'no_bapb',

        'tanggal_masuk',
        'surat_jalan',
        'id_supplier',
        'nama_supplier',
        'id_barang',
        'nama_barang',
        'foto_surat_jalan',

        'jumlah',
        'satuan',
        'status',

        'keterangan',

        'id_karyawan',
        'nama_karyawan',

        'created_at',
        'updated_at',
    ];
}
