<?php

namespace Modules\FullSuscription\Models\Tenant;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatPeriod extends ModelTenant
{
    protected $perPage = 25;

    protected $casts = [
        'active' => 'bool'
    ];

    protected $fillable = [
        'period',
        'name',
        'active'
    ];

    /**
     * @return HasMany
     */
    public function suscription_plans()
    {
        return $this->hasMany(SuscriptionPlan::class);
    }

    /**
     * @return HasMany
     */
    public function user_rel_suscription_plans()
    {
        return $this->hasMany(UserRelSuscriptionPlan::class);
    }

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
    public function setName(string $name): CatPeriod
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }


    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Task
     */
    public function setActive(bool $active): CatPeriod
    {
        $this->active = $active;
        return $this;
    }

}
