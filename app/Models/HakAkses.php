<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HakAkses extends Model
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
    public function Menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }
}
