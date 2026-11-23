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
        'no_bapb',
        'tanggal_masuk',
        'surat_jalan',
        'id_kategori',
        'nama_kategori',
        'id_supplier',
        'nama_supplier',
        'id_barang',
        'kode_barang',
        'nama_barang',
        'satuan',
        'foto_surat_jalan',
        'jumlah',
        'harga',
        'keterangan',
        'status',
        'id_karyawan',
        'nama_karyawan',
        'created_at',
        'updated_at',
    ];
}
