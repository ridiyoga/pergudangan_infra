<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuParent extends Model
{
    protected $table = 'master_menu_parent';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_menu',
        'icon',
        'created_at',
        'updated_at',
    ];
}
