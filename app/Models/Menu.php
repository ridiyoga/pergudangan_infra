<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'master_menu';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_menu',
        'route',
        'icon',
        'id_parent',
        'parent',
        'nama_parent',
        'created_at',
        'updated_at',
    ];
    public function Children(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_parent', 'id')->orderBy('nama_menu');
    }
}
