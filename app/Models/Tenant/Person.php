<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Person
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 */
class Person extends ModelTenant
{
    protected $table = 'persons';
    protected $with = ['identity_document_type', 'country', 'department', 'province', 'district'];
    protected $fillable = [
        'type',
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'email',
        'telephone',
        'perception_agent',
        'state',
        'condition',
        'percentage_perception',
        'person_type_id',
        'comment',
        'enabled',
        'contact',
        'internal_code',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('active', function (Builder $builder) {
    //         $builder->where('status', 1);
    //     });
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(PersonAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @param $query
     * @param $type
     *
     * @return mixed
     */
    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getAddressFullAttribute()
    {
        $address = trim($this->address);
        $address = ($address === '-' || $address === '')?'':$address.' ,';
        if ($address === '') {
            return '';
        }
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function more_address()
    {
        return $this->hasMany(PersonAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person_type()
    {
        return $this->belongsTo(PersonType::class);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereIsEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function getContactAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setContactAttribute($value)
    {
        $this->attributes['contact'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @return array
     */
    //getCollectionData
    public function getCollectionData(){

        $data = [
            'id' => $this->id,
            'description' => $this->number.' - '.$this->name,
            'name' => $this->name,
            'number' => $this->number,
            'identity_document_type_id' => $this->identity_document_type_id,
            'identity_document_type_code' => $this->identity_document_type->code,
            'addresses' => $this->addresses,
            'address' =>  $this->address,
            'internal_code' => $this->internal_code
        ];
        return $data;
    }
}
