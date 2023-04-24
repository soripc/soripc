<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ItemUnitType;

class UnitType extends ModelCatalog
{
    protected $table = "cat_unit_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'symbol',
        'description',
    ];

    public function item_unit_types()
    {
        return $this->hasMany(ItemUnitType::class);
    }
}
