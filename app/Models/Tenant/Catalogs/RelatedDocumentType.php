<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class RelatedDocumentType extends ModelCatalog
{
    protected $table = "cat_related_documents_types";
    public $incrementing = false;
}
