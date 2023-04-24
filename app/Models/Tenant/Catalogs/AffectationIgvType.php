<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\TechnicalServiceItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffectationIgvType extends ModelCatalog
{
    protected $table = "cat_affectation_igv_types";
    public $incrementing = false;

    public function technical_service_item(): HasMany
    {
        return $this->hasMany(TechnicalServiceItem::class, 'affectation_igv_type_id');
    }
}
