<?php

namespace Modules\Dashboard\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNotePayment;
use Carbon\Carbon;
use App\Models\Tenant\Person;
use App\Models\Tenant\Purchase;
use Modules\Expense\Models\Expense;
use Modules\Dashboard\Traits\TotalsTrait;


class DashboardData
{

    use TotalsTrait;

    public function data($request)
    {
// dd($request);
        $establishment_id = $request['establishment_id'];
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];

        $d_start = null;
        $d_end = null;

        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        $customers = Person::whereType('customers')->orderBy('name')->take(100)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return [
            'document' => $this->document_totals($establishment_id, $d_start, $d_end),
            'sale_note' => $this->sale_note_totals($establishment_id, $d_start, $d_end),
            'general' => $this->totals($establishment_id, $d_start, $d_end, $period, $month_start, $month_end),
            'balance' => $this->balance($establishment_id, $d_start, $d_end),
            'customers' => $customers
        ];
    }

    /**
     * @param $establishment_id
     * @param $date_start
     * @param $date_end
     * @return array
     */
    private function sale_note_totals($establishment_id, $date_start, $date_end)
    {

        if($date_start && $date_end){
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)
                                           ->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)->get();
        }

        $sale_note_total = collect($sale_notes)->sum('total');

        $sale_note_total_payment = 0;
        foreach ($sale_notes as $sale_note)
        {
            $sale_note_total_payment += collect($sale_note->payments)->sum('payment');
        }

        $sale_note_total_to_pay = $sale_note_total - $sale_note_total_payment;

        return [
            'totals' => [
                'total_payment' => number_format($sale_note_total_payment,2),
                'total_to_pay' => number_format($sale_note_total_to_pay,2),
                'total' => number_format($sale_note_total,2),
            ],
            'graph' => [
                'labels' => ['Total pagado', 'Total por pagar'],
                'datasets' => [
                    [
                        'label' => 'Notas de venta',
                        'data' => [round($sale_note_total_payment,2), round($sale_note_total_to_pay,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ]
        ];
    }

    /**
     * @param $establishment_id
     * @param $date_start
     * @param $date_end
     * @return array
     */
    private function document_totals($establishment_id, $date_start, $date_end)
    {

        if($date_start && $date_end){
            $documents = Document::query()->where('establishment_id', $establishment_id)->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $documents = Document::query()->where('establishment_id', $establishment_id)->get();
        }
        // $document_total = round(collect($documents)->sum('total'),2);
        $document_total = collect($documents->whereIn('state_type_id', ['01','03','05','07','13'])->whereIn('document_type_id', ['01','03','08']))->sum('total');

        $document_total_note_credit = 0;
        $document_total_payment = 0;

        foreach ($documents as $document)
        {
            if(in_array($document->state_type_id,['01','03','05','07','13']))
                $document_total_payment += collect($document->payments)->sum('payment');

            $document_total_note_credit += ($document->document_type_id == '07') ? $document->total:0; //nota de credito
        }

        $document_total = round(($document_total - $document_total_note_credit),2);

        $document_total_to_pay = $document_total - $document_total_payment;

        return [
            'totals' => [
                'total_payment' => number_format($document_total_payment,2),
                'total_to_pay' => number_format($document_total_to_pay,2),
                'total' => number_format($document_total,2),
            ],
            'graph' => [
                'labels' => ['Total pagado', 'Total por pagar'],
                'datasets' => [
                    [
                        'label' => 'Comprobantes',
                        'data' => [round($document_total_payment,2), round($document_total_to_pay,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ]
        ];
    }

    /**
     * @param $establishment_id
     * @param $date_start
     * @param $date_end
     * @param $period
     * @param $month_start
     * @param $month_end
     * @return array
     */
    private function totals($establishment_id, $date_start, $date_end, $period, $month_start, $month_end)
    {

        if($date_start && $date_end){
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)
                                           ->whereBetween('date_of_issue', [$date_start, $date_end])->get();

            $documents = Document::query()->where('establishment_id', $establishment_id)->whereBetween('date_of_issue', [$date_start, $date_end])->get();

        }else{
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)->get();

            $documents = Document::query()->where('establishment_id', $establishment_id)->get();
        }

        $sale_notes_total = round($sale_notes->sum('total'),2);

        // $documents_total = round($documents->sum('total'),2);
        $documents_total = collect($documents->whereIn('state_type_id', ['01','03','05','07','13'])->whereIn('document_type_id', ['01','03','08']))->sum('total');

// dd($documents->count());

        $document_total_note_credit = 0;

        foreach ($documents as $document)
        {
            $document_total_note_credit += ($document->document_type_id == '07') ? $document->total:0; //nota de credito
        }

        $documents_total = $documents_total - $document_total_note_credit;



        $total = $sale_notes_total + $documents_total;

        if(in_array($period, ['month', 'between_months'])) {
            if($month_start === $month_end) {
                $data_array = $this->getDocumentsByDays($sale_notes, $documents, $date_start, $date_end);
            } else {
                $data_array = $this->getDocumentsByMonths($sale_notes, $documents, $month_start, $month_end);
            }
        } else {
            if($date_start === $date_end) {
                $data_array = $this->getDocumentsByHours($sale_notes, $documents);
            } else {
                $data_array = $this->getDocumentsByDays($sale_notes, $documents, $date_start, $date_end);
            }
        }

        return [
            'totals' => [
                'total_documents' => number_format($documents_total,2),
                'total_sale_notes' => number_format($sale_notes_total,2),
                'total' => number_format($total,2),
            ],
            'graph' => [
                'labels' => array_keys($data_array['total_array']),
                'datasets' => [
                    [
                        'label' => 'Total notas de venta',
                        'data' => array_values($data_array['sale_notes_array']),
                        'backgroundColor' => 'rgb(255, 99, 132)',
                        'borderColor' => 'rgb(255, 99, 132)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                    [
                        'label' => 'Total comprobantes',
                        'data' => array_values($data_array['documents_array']),
                        'backgroundColor' => 'rgb(54, 162, 235)',
                        'borderColor' => 'rgb(54, 162, 235)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                    [
                        'label' => 'Total',
                        'data' => array_values($data_array['total_array']),
                        'backgroundColor' => 'rgb(201, 203, 207)',
                        'borderColor' => 'rgb(201, 203, 207)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ]

                ],
            ]
        ];
    }

    private function getDocumentsByHours($sale_notes, $documents)
    {
        $sale_notes_array = [];
        $documents_array = [];
        $total_array = [];
        $document_total = 0;
        $document_total_note_credit = 0;

        $h_start = 0;
        $h_end = 23;

        for ($h = $h_start; $h <= $h_end; $h++)
        {
            $h_format = str_pad($h, 2, '0', STR_PAD_LEFT);

            $sale_note_total = $sale_notes->filter(function ($row) use($h_format) {
                return substr($row->time_of_issue, 0, 2) === $h_format;
            })->sum('total');
            $sale_notes_array[$h_format.'h'] = $sale_note_total;

            $document_total = $documents->filter(function ($row) use($h_format) {
                return substr($row->time_of_issue, 0, 2) === $h_format;
            })->whereIn('state_type_id', ['01','03','05','07','13'])
            ->whereIn('document_type_id', ['01','03','08'])->sum('total');

            $document_total_note_credit = $documents->filter(function ($row) use($h_format) {
                return substr($row->time_of_issue, 0, 2) === $h_format;
            })->where('document_type_id', '07')->sum('total');

            $document_total = $document_total - $document_total_note_credit;


            $documents_array[$h_format.'h'] = $document_total;

            $total_array[$h_format.'h'] = $sale_note_total + $document_total;
        }

        return compact('sale_notes_array', 'documents_array', 'total_array');
    }

    private function getDocumentsByDays($sale_notes, $documents, $date_start, $date_end)
    {
        $sale_notes_array = [];
        $documents_array = [];
        $total_array = [];
        $document_total = 0;
        $document_total_note_credit = 0;

        $d_start = Carbon::parse($date_start);
        $d_end = Carbon::parse($date_end);

        while ($d_start <= $d_end)
        {
            $sale_note_total = collect($sale_notes)->where('date_of_issue', $d_start)->sum('total');
            $sale_notes_array[$d_start->format('d').'d'] = $sale_note_total;

            $document_total = collect($documents)->whereIn('state_type_id', ['01','03','05','07','13'])
                                                 ->whereIn('document_type_id', ['01','03','08'])
                                                 ->where('date_of_issue', $d_start)->sum('total');

            $document_total_note_credit = collect($documents)->where('document_type_id', '07')->where('date_of_issue', $d_start)->sum('total');

            $document_total = $document_total - $document_total_note_credit;

            $documents_array[$d_start->format('d').'d'] = $document_total;

            $total_array[$d_start->format('d').'d'] = $sale_note_total + $document_total;

            $d_start = $d_start->addDay();
        }

        return compact('sale_notes_array', 'documents_array', 'total_array');
    }

    private function getDocumentsByMonths($sale_notes, $documents, $month_start, $month_end)
    {
        $sale_notes_array = [];
        $documents_array = [];
        $total_array = [];
        $document_total = 0;
        $document_total_note_credit = 0;

        $m_start = (int) substr($month_start, 5, 2);
        $m_end = (int) substr($month_end, 5, 2);
//        dd($m_start);
        for ($m = $m_start; $m <= $m_end; $m++)
        {
            $m_format = str_pad($m, 2, '0', STR_PAD_LEFT);

            $sale_note_total = $sale_notes->filter(function ($row) use($m_format) {
//                dd($row->date_of_issue->format('m'));
                return $row->date_of_issue->format('m') === $m_format;
            })->sum('total');
            $sale_notes_array[$m_format.'m'] = $sale_note_total;

            $document_total = $documents->filter(function ($row) use($m_format) {
                return $row->date_of_issue->format('m') === $m_format;
            })->whereIn('state_type_id', ['01','03','05','07','13'])
            ->whereIn('document_type_id', ['01','03','08'])->sum('total');

            $document_total_note_credit = $documents->filter(function ($row) use($m_format) {
                return $row->date_of_issue->format('m') === $m_format;
            })->where('document_type_id', '07')->sum('total');

            $document_total = $document_total - $document_total_note_credit;

            $documents_array[$m_format.'m'] = $document_total;

            $total_array[$m_format.'m'] = $sale_note_total + $document_total;
        }

        return compact('sale_notes_array', 'documents_array', 'total_array');
    }





    private function balance($establishment_id, $date_start, $date_end){

        $document = $this->get_document_totals($establishment_id, $date_start, $date_end);
        $sale_note = $this->get_sale_note_totals($establishment_id, $date_start, $date_end);
        $purchase = $this->get_purchase_totals($establishment_id, $date_start, $date_end);
        $expense = $this->get_expense_totals($establishment_id, $date_start, $date_end);

        $response_totals_document = $document['totals'];
        $response_totals_sale_note = $sale_note['totals'];
        $response_totals_purchase = $purchase['totals'];
        $response_totals_expense = $expense['totals'];

        // dd($response_totals_document, $response_totals_sale_note, $response_totals_purchase, $response_totals_expense);

        $total_document =  $response_totals_document['total'];
        $total_payment_document =  $response_totals_document['total_payment'];

        $total_sale_note =  $response_totals_sale_note['total'];
        $total_payment_sale_note =  $response_totals_sale_note['total_payment'];
        
        $total_purchase = $response_totals_purchase['total'];
        $total_payment_purchase = $response_totals_purchase['total_payment'];

        $total_expense = $response_totals_expense['total'];
        $total_payment_expense = $response_totals_expense['total_payment'];

        $all_totals = $total_document + $total_sale_note - $total_expense - $total_purchase;
        $all_totals_payment = $total_payment_document + $total_payment_sale_note - $total_payment_purchase - $total_payment_expense ;

        return [
            'totals' => [
                'total_document' => number_format($total_document,2),
                'total_payment_document' => number_format($total_payment_document,2),
                'total_sale_note' => number_format($total_sale_note,2),
                'total_payment_sale_note' => number_format($total_payment_sale_note,2),
                'total_purchase' => number_format($total_purchase,2),
                'total_payment_purchase' => number_format($total_payment_purchase,2),
                'total_expense' => number_format($total_expense,2),
                'total_payment_expense' => number_format($total_payment_expense,2),

                'all_totals' => number_format($all_totals,2),
                'all_totals_payment' => number_format($all_totals_payment,2),
            ],
            'graph' => [
                'labels' => ['Totales', 'Total pagos'],
                'datasets' => [
                    [
                        'label' => 'Grafico',
                        'data' => [round($all_totals,2), round($all_totals_payment,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ] 
        ];
    }


}
