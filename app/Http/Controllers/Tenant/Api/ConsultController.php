<?php
namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use App\Models\Tenant\SummaryDocument;
use Exception;
use Illuminate\Http\Request;

class ConsultController extends Controller
{
    public function __construct()
    {

    }

    public function summarieCpe(Request $request)
    {
        if($request->series) {
            $document = Document::where('series', $request->series)->where('number', $request->number)->first();
            if(!$document) {
                return [
                    'success' => false,
                    'data' => "Los datos: {$request->series}-{$request->number} no se encuentra registrado"
                ];
            }else {
                $summarieDocument = SummaryDocument::where('document_id', $document->id)->first();
                if(!$summarieDocument) {
                    return [
                        'success' => false,
                        'document_external_id' => $document->external_id,
                        'document_state_type' => $document->state_type_id,
                        'data' => "No se encuentra Resumen con la boleta ingresada"
                    ];
                }else {
                    $summarie = Summary::where('id', $summarieDocument->summary_id)->first();
                    return [
                        'success' => true,
                        'document_external_id' => $document->external_id,
                        'document_state_type' => $document->state_type_id,
                        'message' => "Resumen encontrado",
                        'data' => $summarie
                    ];
                }
            }
        } else {
            return [
                'success' => false,
                'data' => "Es requerido la serie del documento y el correlativo"
            ];
        }
    }
}