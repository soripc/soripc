<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class CatItemMoldProperty extends ModelTenant
{
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
    public function setName(string $name): CatItemMoldProperty
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }
}
