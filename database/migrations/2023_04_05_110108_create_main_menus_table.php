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
        Schema::create('main_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id')->comment('Restaurant ID')->nullable()->index();
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onDelete('cascade');
            $table->string('main_menu_name', 150);
            $table->string('main_menu_image', 150)->nullable();
            $table->text('main_menu_description')->nullable();
            $table->unique(['restaurant_id', 'main_menu_name']);
            $table->json('lang_main_menu_name')->nullable();
            $table->integer('sort_order')->default(0);
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
        Schema::dropIfExists('main_menus');
    }
};
