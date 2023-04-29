<?php

use App\Models\System\Client;
use App\Models\Tenant\Company;
use App\Models\Tenant\Task;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemAddSoapTypeIdToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->char('soap_type_id', 2)->default('01')->after('token');
            $table->json('tasks')->nullable()->after('start_billing_cycle');
        });

        $clients = Client::query()
            ->with('hostname', 'hostname.website')
            ->get();

        foreach ($clients as $row) {
            $tenancy = app(Environment::class);
            if($row->hostname) {
                $tenancy->tenant($row->hostname->website);
                $company = Company::query()->select('id', 'soap_type_id')->first();
                $soap_type_id = $company->soap_type_id;
                $tasks = Task::query()->get()->toArray();
                $row->update([
                    'soap_type_id' => $soap_type_id,
                    'tasks' => $tasks
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('soap_type_id');
            $table->dropColumn('tasks');
        });
    }
}
