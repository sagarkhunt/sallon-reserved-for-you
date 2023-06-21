<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreEmpCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_emp_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_emp_id')->nullable();
            $table->foreign('store_emp_id')->references('id')->on('store_emps')->onDelete('cascade')->onUpdate('cascade');
            $table->string('category_type')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');                        
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
        Schema::dropIfExists('store_emp_categories');
    }
}
