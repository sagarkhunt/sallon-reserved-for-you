<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->text('plan_description');
            $table->string('price');
            $table->string('recruitment_fee')->nullable();
            $table->string('tax_fee')->nullable();
            $table->enum('is_management_tool',['yes','no'])->default('no');
            $table->enum('is_profile_design',['yes','no'])->default('no');
            $table->enum('is_booking_system',['yes','no'])->default('no');
            $table->enum('is_ads_newsletter',['yes','no'])->default('no');
            $table->enum('is_social_media_platforms',['yes','no'])->default('no');
            $table->enum('status',['active','inactive'])->default('active');
            $table->string('plan_actual_name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
