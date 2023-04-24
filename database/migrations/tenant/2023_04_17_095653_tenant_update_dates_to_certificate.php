<?php

use Illuminate\Database\Migrations\Migration;

class TenantUpdateDatesToCertificate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        function_certificate_update_dates();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
