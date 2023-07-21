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
        Schema::create('food_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->comment('Restaurant ID')->nullable()->index();
            // $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');
            $table->string('food_types_name', 150);
            $table->string('food_types_image', 150)->nullable();
            $table->unique(['restaurant_id', 'food_types_name']);
            $table->json('lang_food_types_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_types');
    }
};
