<?php

namespace App\Models\Tenant\Catalogs;

class AddressType extends ModelCatalog
{
    protected $table = 'cat_address_types';

    public $incrementing = false;
    public $timestamps = false;
}
