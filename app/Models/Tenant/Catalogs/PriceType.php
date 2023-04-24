<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\TechnicalServiceItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceType extends ModelCatalog
{
    protected $table = "cat_price_types";
    public $incrementing = false;

    public  function technical_service_item():HasMany
    {
        return $this->hasMany(TechnicalServiceItem::class, 'price_type_id');
    }
}
