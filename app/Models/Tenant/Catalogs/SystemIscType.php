<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\TechnicalServiceItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemIscType extends ModelCatalog
{
    protected $table = "cat_system_isc_types";
    public $incrementing = false;

    public function technical_service_item():HasMany
    {
        return $this->hasMany(TechnicalServiceItem::class, 'system_isc_type_id');
    }
}
