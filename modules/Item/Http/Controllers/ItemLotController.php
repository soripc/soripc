<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Item\Models\ItemLot;
use Modules\Item\Http\Resources\ItemLotCollection;
use Modules\Item\Http\Requests\ItemLotRequest;
use Modules\Item\Exports\ItemLotExport;
use App\Models\Tenant\Warehouse;
use Carbon\Carbon;

class ItemLotController extends Controller
{
    public function index()
    {
        return view('item::item-lots.index');
    }

    public function columns()
    {
        return [
            'warehouses' => Warehouse::all(),
            'columns' => [
                'series' => 'Serie',
                'date' => 'Fecha',
                'state' => 'Estado',
                'item_description' => 'Producto',
            ]
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);

        return new ItemLotCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function getRecords($request)
    {
        $records = ItemLot::query();

        if($request->column == 'item_description'){
            $records->whereHas('item', function($query) use($request){
                $query->where('description', 'like', "%{$request->value}%");
            });
        } else {
            $records->where($request->column, 'like', "%{$request->value}%");
        }

        if(intval($request->vendido) >= 0) {
            $records->where('has_sale', $request->vendido);
        }

        if($request->estable) {
            $records->where('warehouse_id', $request->estable);
        }

        /*if($request->column == 'item_description'){

            $records = ItemLot::whereHas('item', function($query) use($request){
                            $query->where('description', 'like', "%{$request->value}%")->latest();
                        });

        }else{
            $records = ItemLot::where($request->column, 'like', "%{$request->value}%")->latest();
        }*/

        return $records->latest();
    }

    public function record($id)
    {
        $record = ItemLot::findOrFail($id);

        return $record;
    }

    public function store(ItemLotRequest $request)
    {
        $id = $request->input('id');
        $record = ItemLot::findOrFail($id);
        $record->series = $request->series;
        $record->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Serie editada con éxito' : 'Serie registrada con éxito',
        ];
    }

    public function export(Request $request)
    {
        $records = $this->getRecords($request)->get();

        return (new ItemLotExport)
            ->records($records)
            ->download('Series_' . Carbon::now() . '.xlsx');
    }
}
