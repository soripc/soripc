<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataCertificateToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->date('certificate_date_of_issue')->nullable()->after('certificate');
            $table->date('certificate_date_of_due')->nullable()->after('certificate_date_of_issue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('certificate_date_of_issue');
            $table->dropColumn('certificate_date_of_due');
        });
    }
}
