<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class CatItemMoldCavity extends ModelTenant
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
    public function setName(string $name): CatItemMoldCavity
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }
}
