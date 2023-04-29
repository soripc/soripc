<?php

namespace Modules\Company\Observers;

use App\Models\Tenant\Company;

class CompanyObserver
{
    public function saved(Company $company)
    {
        update_client_data([
            'soap_type_id' => $company->soap_type_id
        ]);
    }
}
