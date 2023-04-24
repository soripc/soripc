<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class RetentionType extends ModelCatalog
{
    protected $table = "cat_retention_types";
    public $incrementing = false;
}
