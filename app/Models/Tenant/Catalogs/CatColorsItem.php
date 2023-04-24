<?php

namespace App\Models\Tenant\Catalogs;

class CatColorsItem extends ModelCatalog
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
     * @return CatColorsItem
     */
    public function setName(string $name): CatColorsItem
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }


}
