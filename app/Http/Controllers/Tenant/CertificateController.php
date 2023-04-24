<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Certificate\GenerateCertificate;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant\Configuration;

class CertificateController extends Controller
{
    public function record()
    {
        $company = Company::active();
        $configuration = Configuration::first();

        return [
            'certificate' => $company->certificate,
            'certificate_date_of_issue' => optional($company->certificate_date_of_issue)->format('d/m/Y'),
            'certificate_date_of_due' => optional($company->certificate_date_of_due)->format('d/m/Y'),
            'config_system_env' => (bool)$configuration->config_system_env
        ];
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $company = Company::query()->first();
                $password = $request->input('password');
                $filename = $request->file('file');
                $extension = $filename->getClientOriginalExtension();

                if (!in_array($extension, ['pfx', 'p12', 'pem'])) {
                    return [
                        'success' => false,
                        'message' => 'La extensión del archivo es incorrecta.'
                    ];
                }

                if ($extension === 'pem') {
                    $pem_content = $filename->get();
                    $pem = GenerateCertificate::loadPEM($pem_content);
                } else {
                    $pfx = $filename->get();
                    $pem = GenerateCertificate::typePEM($pfx, $password);
                    $pem_content = $pem['file_content'];
                }

                $message = 'El certificado con extensión (PEM) fue generado satisfactoriamente.';

                $date_of_issue = Carbon::parse($pem['date_of_issue'])->format('Y-m-d');
                $date_of_due = Carbon::parse($pem['date_of_due'])->format('Y-m-d');

                $new_filename = 'certificate_' . $company->number . '_' . date('Ymd_His') . '.pem';

                if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates'))) {
                    mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates'));
                }
                file_put_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates' . DIRECTORY_SEPARATOR . $new_filename), $pem_content);
                $company->certificate = $new_filename;
                $company->certificate_date_of_issue = $date_of_issue;
                $company->certificate_date_of_due = $date_of_due;
                $company->save();

                return [
                    'success' => true,
                    'message' => $message,
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' => __('app.actions.upload.error'),
        ];
    }

    public function destroy()
    {
        $company = Company::active();
        $company->certificate = null;
        $company->save();

        return [
            'success' => true,
            'message' => 'Cliente eliminado con éxito'
        ];
    }
}
