<?php

use App\CoreFacturalo\Helpers\Certificate\GenerateCertificate;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use Carbon\Carbon;

if (!function_exists('func_str_to_upper_utf8')) {
    function func_str_to_upper_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtoupper($text, 'utf-8');
    }
}

if (!function_exists('func_str_to_lower_utf8')) {
    function func_str_to_lower_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtolower($text, 'utf-8');
    }
}

if (!function_exists('func_filter_items')) {
    function func_filter_items($query, $text)
    {
        $text_array = explode(' ', $text);
        foreach ($text_array as $txt) {
            $trim_txt = trim($txt);
            $query->where('text_filter', 'like', "%$trim_txt%");
        }

        return $query;
    }
}

if (!function_exists('function_certificate_update_dates')) {
    function function_certificate_update_dates()
    {
        $company = Company::query()->first();
        if ($company && $company->certificate) {
            $filename = storage_path('app' . DIRECTORY_SEPARATOR .
                'certificates' . DIRECTORY_SEPARATOR . $company->certificate);

            $pem_content = file_get_contents($filename);
            $pem = GenerateCertificate::loadPEM($pem_content);

            $date_of_issue = Carbon::parse($pem['date_of_issue'])->format('Y-m-d');
            $date_of_due = Carbon::parse($pem['date_of_due'])->format('Y-m-d');

            $company->certificate_date_of_issue = $date_of_issue;
            $company->certificate_date_of_due = $date_of_due;
            $company->save();
        }
    }
}

if (!function_exists('func_get_establishments_show')) {
    function func_get_establishments_show()
    {
        return Establishment::query()
            ->with('district', 'province', 'department')
            ->where('id', auth()->user()->establishment_id)
            ->get()
            ->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->description,
                    'address' => $row->address .
                        ', ' . optional($row->department)->description .
                        ' - ' . optional($row->province)->description .
                        ' - ' . optional($row->district)->description,
                    'email' => $row->email,
                    'telephone' => $row->telephone,
                ];
            });
    }
}

if (!function_exists('func_is_windows')) {
    function func_is_windows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}

if (!function_exists('func_is_demo_platform')) {
    function func_is_demo_platform()
    {
        $company = Company::query()->select('id', 'is_demo_platform')->first();
        if ($company->is_demo_platform) {
            throw new Exception('Se encuentra en una plataforma DEMO, no es posible realizar la operación solicitada');
        }
    }
}
