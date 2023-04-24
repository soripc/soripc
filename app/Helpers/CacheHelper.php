<?php

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use Illuminate\Support\Facades\Cache;

if (!function_exists('func_get_table_locations')) {
    function func_get_table_locations()
    {
        if (Cache::has('table_locations')) {
            return Cache::get('table_locations');
        }

        $locations = [];
        $departments = Department::query()
            ->with('provinces', 'provinces.districts')
            ->get();
        foreach ($departments as $department) {
            $children_provinces = [];
            foreach ($department->provinces as $province) {
                $children_districts = [];
                foreach ($province->districts as $district) {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => func_str_to_upper_utf8($district->id . " - " . $district->description)
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => func_str_to_upper_utf8($province->description),
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => func_str_to_upper_utf8($department->description),
                'children' => $children_provinces
            ];
        }

        Cache::put('table_locations', $locations, 1440);

        return $locations;
    }
}

if (!function_exists('func_get_table_countries')) {
    function func_get_table_countries()
    {
        if (Cache::has('table_countries')) {
            return Cache::get('table_countries');
        }

        $records = Country::query()
            ->get();

        Cache::put('table_countries', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_operation_types')) {
    function func_get_table_operation_types()
    {
        if (Cache::has('table_operation_types')) {
            return Cache::get('table_operation_types');
        }

        $records = OperationType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_operation_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_affectation_igv_types')) {
    function func_get_table_affectation_igv_types()
    {
        if (Cache::has('table_affectation_igv_types')) {
            return Cache::get('table_affectation_igv_types');
        }

        $records = AffectationIgvType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_affectation_igv_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_identity_document_types')) {
    function func_get_table_identity_document_types()
    {
        if (Cache::has('table_identity_document_types')) {
            return Cache::get('table_identity_document_types');
        }

        $records = IdentityDocumentType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_identity_document_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_currency_types')) {
    function func_get_table_currency_types()
    {
        if (Cache::has('table_currency_types')) {
            return Cache::get('table_currency_types');
        }

        $records = CurrencyType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_currency_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_attribute_types')) {
    function func_get_table_attribute_types()
    {
        if (Cache::has('table_attribute_types')) {
            return Cache::get('table_attribute_types');
        }

        $records = AttributeType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_attribute_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_system_isc_types')) {
    function func_get_table_system_isc_types()
    {
        if (Cache::has('table_system_isc_types')) {
            return Cache::get('table_system_isc_types');
        }

        $records = SystemIscType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_system_isc_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_price_types')) {
    function func_get_table_price_types()
    {
        if (Cache::has('table_price_types')) {
            return Cache::get('table_price_types');
        }

        $records = PriceType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_price_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_discount_types_item')) {
    function func_get_table_discount_types_item()
    {
        if (Cache::has('table_discount_types_item')) {
            return Cache::get('table_discount_types_item');
        }

        $records = ChargeDiscountType::query()
            ->where('type', 'discount')
            ->where('level', 'item')
            ->where('active', true)
            ->get();

        Cache::put('table_discount_types_item', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_charge_types_item')) {
    function func_get_table_charge_types_item()
    {
        if (Cache::has('table_charge_types_item')) {
            return Cache::get('table_charge_types_item');
        }

        $records = ChargeDiscountType::query()
            ->where('type', 'charge')
            ->where('level', 'item')
            ->where('active', true)
            ->get();

        Cache::put('table_charge_types_item', $records, 1440);

        return $records;
    }
}
