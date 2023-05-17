<?php

namespace Modules\SaleNote\Http\Controllers;

use App\Models\Tenant\SaleNote;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SaleNoteController extends Controller
{
    public function searchByPersonId(Request $request)
    {
        $person_id = $request->input('person_id');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $records = SaleNote::query()
            ->select('id', 'series', 'number', 'date_of_issue', 'total', 'currency_type_id')
            ->with('currency_type')
            ->where('customer_id', $person_id)
            ->whereNull('document_id')
            ->whereIn('state_type_id', ['01', '03', '05'])
            ->whereBetween('date_of_issue', [$date_start, $date_end])
            ->orderBy('number', 'desc')
            ->get()
            ->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'number' => $row->series . '-' . $row->number,
                    'date_of_issue' => $row->date_of_issue->format('d/m/Y'),
                    'currency_type_id' => $row->currency_type_id,
                    'total_label' => $row->currency_type->symbol.' '.$row->total,
                    'total' => $row->total,
                    'selected' => false,
                ];
            });

        return $records;
    }
}
