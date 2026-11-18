<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $table = 'master_jabatan';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_jabatan',
        'created_at',
        'updated_at',
    ];
    public function HakAkses(): HasMany
    {
        return $this->hasMany(HakAkses::class, 'id_jabatan', 'id')->orderBy('nama_menu');
    }
}
