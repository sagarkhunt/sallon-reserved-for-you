<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalAvgRatingToStoreRatingReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_rating_reviews', function (Blueprint $table) {
            $table->double('total_avg_rating',15,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_rating_reviews', function (Blueprint $table) {
            $table->dropColumn(['total_avg_rating']);
        });
    }
}
