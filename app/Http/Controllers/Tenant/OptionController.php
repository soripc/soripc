<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Retention;
use App\Models\Tenant\Perception;
use App\Models\Tenant\Summary;
use App\Models\Tenant\Voided;
use Illuminate\Http\Request;
use App\Models\Tenant\Configuration;
use Modules\Expense\Models\Expense;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Finance\Models\GlobalPayment; 
use Modules\Purchase\Models\PurchaseQuotation;
use Modules\Inventory\Models\{
    ItemWarehouse,
    InventoryKardex
};

class OptionController extends Controller
{

    protected $delete_quantity;

    public function create()
    {
        return view('tenant.options.form');
    }

    public function deleteDocuments(Request $request)
    {

        $this->delete_quantity = 0;

        Summary::where('soap_type_id', '01')->delete();
        Voided::where('soap_type_id', '01')->delete();
        
        //Purchase
        $this->deleteInventoryKardex(Purchase::class);

        Purchase::where('soap_type_id', '01')->delete();
        
        PurchaseOrder::where('soap_type_id', '01')->delete();
        PurchaseQuotation::where('soap_type_id', '01')->delete();

        $quantity = Document::where('soap_type_id', '01')->count();

        //Document
        $this->deleteInventoryKardex(Document::class);

        Document::where('soap_type_id', '01')
        ->whereIn('document_type_id', ['07', '08'])->delete();        
        Document::where('soap_type_id', '01')->delete();

        $this->update_quantity_documents($quantity);

        Retention::where('soap_type_id', '01')->delete();
        Perception::where('soap_type_id', '01')->delete();

        //SaleNote
        $sale_notes = SaleNote::where('soap_type_id', '01')->get();
        SaleNote::where('soap_type_id', '01')->delete();
        $this->deleteInventoryKardex(SaleNote::class, $sale_notes);

        Quotation::where('soap_type_id', '01')->delete();
        Expense::where('soap_type_id', '01')->delete();
        
        GlobalPayment::where('soap_type_id', '01')->delete();

        $this->updateStockAfterDelete();

        return [
            'success' => true,
            'message' => 'Documentos de prueba eliminados',
            'delete_quantity' => $this->delete_quantity,
        ];
    }

    private function deleteInventoryKardex($model, $records = null){

        if(!$records){
            $records = $model::where('soap_type_id', '01')->get();
        }

        $this->delete_quantity += $records->count();

        foreach ($records as $record) {

            $record->inventory_kardex()->delete();

        }
    }

    private function updateStockAfterDelete(){

        if($this->delete_quantity > 0){

            ItemWarehouse::latest()->update([
                'stock' => 0
            ]);

        }

    }

    private function update_quantity_documents($quantity)
    {  
        $configuration = Configuration::first();
        $configuration->quantity_documents -= $quantity; 
        $configuration->save();        
    }
    
}