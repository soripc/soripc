<?php

namespace Modules\Production\Models;

use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tenant\SoapType;

class Packaging extends ModelTenant
{
    protected $table = 'packaging';

    protected $casts = [
        'item_id' => 'int',
        'user_id' => 'int',
        'establishment_id' => 'int',
        'quantity' => 'float',
        'number_packages' => 'float'
    ];
    protected $fillable = [
        'name',
        'item_id',
        'user_id',
        'establishment_id',
        'item_extra_data',
        'quantity',
        'number_packages',
        'item',
        'observation',
        'lot_code',
        'date_start',
        'time_start',
        'date_end',
        'time_end',
        'packaging_collaborator',
        'soap_type_id',
    ];

    /**
     * @return BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function getCollectionData()
    {
        $data = $this->toArray();
        $data['item'] = $this->item;
        $data['user'] = $this->user->name;
        $data['quantity'] = $this->quantity;
        $data['item_name'] = $this->item->description;
        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $data['stablishment'] = $this->establishment->description;
        return
            $data;
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getItemExtraDataAttribute($value)
    {
        return (null === $value) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setItemExtraDataAttribute($value)
    {
        $this->attributes['item_extra_data'] = (null === $value) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getItemAttribute($value)
    {
        return (null === $value) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (null === $value) ? null : json_encode($value);
    }
}
