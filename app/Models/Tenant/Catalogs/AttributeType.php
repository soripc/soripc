<?php

namespace App\Models\Tenant\Catalogs;

class AttributeType extends ModelCatalog
{
    protected $table = "cat_attribute_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'description',
    ];
}
