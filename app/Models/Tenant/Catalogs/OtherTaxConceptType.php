<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class OtherTaxConceptType extends ModelCatalog
{
    protected $table = "cat_other_tax_concept_types";
    public $incrementing = false;
}
