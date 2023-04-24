<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class CatItemUnitBusiness extends ModelTenant
{
    protected $table = 'cat_item_unit_business';
    protected $perPage = 25;

    protected $fillable = [
        'name'
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): CatItemUnitBusiness
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }
}
