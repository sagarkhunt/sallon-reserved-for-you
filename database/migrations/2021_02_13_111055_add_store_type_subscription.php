<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreTypeSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->string('store_active_actual_plan');
            $table->string('store_active_plan');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->string('slug_actual_plan');
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
            $table->dropColumn(['store_active_actual_plan','store_active_plan']);
        });
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['slug_actual_plan']);
        });
    }
}
