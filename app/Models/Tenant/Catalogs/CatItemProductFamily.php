<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class CatItemProductFamily extends ModelTenant
{
    protected $table = 'cat_item_product_family';
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
    public function setName(string $name): CatItemProductFamily
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }
}
