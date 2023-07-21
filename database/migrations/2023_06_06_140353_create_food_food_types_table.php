<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_food_types', function (Blueprint $table) {
            $table->unsignedBigInteger('food_id')->index();
            $table->unsignedBigInteger('food_types_id')->index();
            // $table->foreign('food_types_id')->references('id')->on('food_categories')->onDelete('cascade');
            // $table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
            $table->primary(['food_id', 'food_types_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_food_types');
    }
};
