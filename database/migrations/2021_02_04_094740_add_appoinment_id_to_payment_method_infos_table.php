<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppoinmentIdToPaymentMethodInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_infos', function (Blueprint $table) {            
            $table->dropColumn(['date', 'time']);
        });
         Schema::table('payment_method_infos', function (Blueprint $table) {
            $table->integer('appoinment_id')->nullable();
            $table->string('payment_method')->nullable();
        });
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
