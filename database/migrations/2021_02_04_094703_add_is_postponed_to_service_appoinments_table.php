<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPostponedToServiceAppoinmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_appoinments', function (Blueprint $table) {
            $table->string('is_postponed')->nullable();
            $table->string('cancel_reson')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_appoinments', function (Blueprint $table) {
            $table->dropColumn(['is_postponed','cancel_reson']);
        });
    }
}
