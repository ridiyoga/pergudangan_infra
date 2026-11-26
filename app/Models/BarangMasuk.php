<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',

        'no_bapb',

        'tanggal_masuk',

        'id_supplier',
        'nama_supplier',

        'id_karyawan',
        'nama_karyawan',
        'ttd_karyawan',

        'id_atasan1',
        'nama_atasan1',
        'ttd_atasan1',
        'waktu_atasan1',
        'status_atasan1',
        'alasan_ditolak1',

        'id_atasan2',
        'nama_atasan2',
        'ttd_atasan2',
        'waktu_atasan2',
        'status_atasan2',
        'alasan_ditolak2',

        'id_atasan3',
        'nama_atasan3',
        'ttd_atasan3',
        'waktu_atasan3',
        'status_atasan3',
        'alasan_ditolak3',

        'id_atasan4',
        'nama_atasan4',
        'ttd_atasan4',
        'waktu_atasan4',
        'status_atasan4',
        'alasan_ditolak4',

        'status',

        'id_proyek',
        'nama_proyek',
        'id_area',
        'nama_area',

        'created_at',
        'updated_at',
    ];
}
