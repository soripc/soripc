<?php

namespace Modules\Production\Models;

use App\Models\Tenant\ModelTenant;

class Machine extends ModelTenant
{
    protected $perPage = 25;

    protected $casts = [
        'machine_type_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'machine_type_id',
        'brand',
        'model',
        'closing_force'
    ];

    public function machine_type()
    {
        return $this->belongsTo(MachineType::class);
    }

    public function getCollectionData()
    {

        $data = $this->toArray();
        $data['machine_type'] = $this->machine_type;
        return $data;
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}

