<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantOthersToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('documents', function (Blueprint $table) {
//            $table->string('payment_condition_id', 2)->change();
//            $table->string('currency_type_id', 3)->change();
//            $table->string('document_type_id', 4)->change();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('documents', function (Blueprint $table) {
//            $table->string('currency_type_id')->change();
//            $table->string('payment_condition_id')->change();
//            $table->string('document_type_id')->change();
//        });
    }
}
