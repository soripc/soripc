<?php

namespace Modules\Suscription\Models\Tenant;

use App\Models\Tenant\ModelTenant;

class SuscriptionGrade extends ModelTenant
{
    protected $table = 'suscription_grade';
    public $timestamps = false;

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
     *
     * Obtener datos para el listado y edicion
     *
     * @return array
     */
    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}
