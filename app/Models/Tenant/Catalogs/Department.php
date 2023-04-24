<?php

namespace App\Models\Tenant\Catalogs;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description'
    ];

    static function idByDescription($description)
    {
        $department = Department::where('description', $description)->first();
        if ($department) {
            return $department->id;
        }
        return '15';
    }

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }
}
