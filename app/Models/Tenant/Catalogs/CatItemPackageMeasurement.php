<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\ModelTenant;

class CatItemPackageMeasurement extends ModelTenant
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
    public function setName(string $name): CatItemPackageMeasurement
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }
}
