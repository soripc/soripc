<?php

namespace Modules\Purchase\Observers;

use App\Models\Tenant\Purchase;

class PurchaseObserver
{
    public function creating(Purchase $purchase)
    {
        $number_unique = join('-', [
            $purchase->soap_type_id,
            $purchase->supplier_id,
            $purchase->document_type_id,
            $purchase->series,
            $purchase->number
        ]);
        $purchase->number_unique = $number_unique;
    }
}
