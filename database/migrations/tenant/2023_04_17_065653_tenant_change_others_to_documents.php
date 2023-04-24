<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeOthersToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['currency_type_id']);
            $table->dropForeign(['payment_condition_id']);
            $table->dropForeign(['document_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('payment_condition_id')->references('id')->on('payment_conditions');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('document_type_id')->references('id')->on('cat_document_types');
        });
    }
}
