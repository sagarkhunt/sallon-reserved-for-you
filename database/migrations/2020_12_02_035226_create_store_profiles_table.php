<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('store_name');
            $table->time('store_start_time')->nullable();
            $table->time('store_end_time')->nullable();
            $table->string('store_contact_number')->nullable();
            $table->string('store_district')->nullable();
            $table->string('store_link_id')->nullable();
            $table->longText('store_description')->nullable();
            $table->string('store_profile')->nullable();
            $table->string('store_banner')->nullable();
            $table->enum('store_status',['active','inactive','pending'])->nullable();
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
        Schema::dropIfExists('store_profiles');
    }
}
