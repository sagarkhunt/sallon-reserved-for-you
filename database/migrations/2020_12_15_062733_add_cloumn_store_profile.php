<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloumnStoreProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->text('store_address');
            $table->text('zipcode')->nullable();
            $table->text('payment_method')->nullable();
            $table->text('cancellation_deadline')->nullable();
            $table->text('appointment_contingent')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->dropColumn(['store_address','zipcode','payment_method','cancellation_deadline','appointment_contingent']);
        });
    }
}
