<?php

namespace Modules\Production\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tenant\SoapType;

class Production extends ModelTenant
{
    protected $table = 'production';
    protected $perPage = 25;


    protected $casts = [
        'user_id' => 'int',
        'item_id' => 'int',
        'quantity' => 'float',
        'inventory_id_reference' => 'int',
        'machine_id' => 'int',
        'informative' => 'bool',
        'agreed' => 'float',
        'imperfect' => 'float'
    ];

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'inventory_id_reference',
        'machine_id',
        'production_order',
        'name',
        'date_start',
        'time_start',
        'date_end',
        'time_end',
        'comment',
        'informative',
        'lot_code',
        'item_extra_data',
        'proccess_type',
        'mix_date_start',
        'mix_time_start',
        'mix_date_end',
        'mix_time_end',
        'agreed',
        'imperfect',
        'production_collaborator',
        'mix_collaborator',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function getCollectionData()
    {

        $data = $this->toArray();
        $data['machine'] = $this->machine;
        $data['item'] = $this->item;
        $data['item_supply'] = $this->item->supplies;
        $data['user'] = $this->user->name;
        $data['quantity'] = $this->quantity;
        $data['item_name'] = $this->item->description;
        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');

        $item_extra_data = (array)$this->item_extra_data;
        $data['color'] = null;
        if (isset($item_extra_data ['color'])) {
            $colorId = (int)$item_extra_data ['color'];
            $itemColor = \App\Models\Tenant\ItemColor::find($colorId);
            $color = $itemColor->getColor()->name;
            $data['color'] = $color;
        }
        return $data;
    }


    public function machine()
    {
        return $this->belongsTo(Machine::class);
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
}
