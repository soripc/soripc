<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class PerceptionType extends ModelCatalog
{
    protected $table = "cat_perception_types";
    public $incrementing = false;
}
