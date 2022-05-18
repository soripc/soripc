<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\PersonType;
use Modules\Item\Models\ListPrice;

/**
 * App\Models\Tenant\ItemUnitType
 *
 * @property-read \App\Models\Tenant\Item $item
 * @property-read UnitType $unit_type
 * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemUnitType query()
 * @mixin \Eloquent
 */
class NamePrice extends ModelTenant
{
     protected $with = ['unit_type', 'list_price'];
    public $timestamps = false;

    protected $fillable = [
        'description',
        'type_customer_id',
        'unit_type_id',
        'quantity_unit',
        'price_default'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit_type() {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function person_type(){
        return $this->belongsTo(UnitType::class, 'type_customer_id');
    }

    public function list_price()
    {
        return $this->hasMany(ListPrice::class,'name_price_id','name_price_id');
    }

    public function scopeWhereIdPrice()
    {
        $name_id=NamePrice::latest('id')->first();
        $id = 1;
        if(empty($name_id->id)){
            $id=1;
        }else{
            $id=$name_id->id;
        }
        return $id;
    }


    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param int $decimal_units
     *
     * @return array
     */
    public function getCollectionData($decimal_units = 2){

        return [
            'id'            => $this->id,
            'description'   => "{$this->description}",
            'type_customer_id'       => $this->item_id,
            'unit_type_id'  => $this->unit_type_id,
            'quantity_unit' => number_format($this->quantity_unit, $decimal_units, '.', ''),
            'price1'        => number_format($this->price1, $decimal_units, '.', ''),
            'price2'        => number_format($this->price2, $decimal_units, '.', ''),
            'price3'        => number_format($this->price3, $decimal_units, '.', ''),
            'price4'        => number_format($this->price4, $decimal_units, '.', ''),
            'price_default' => $this->price_default,

            /*
            'price1' => $row->price1,
            'price2' => $row->price2,
            'price3' => $row->price3,
            */


        ];
    }

}
