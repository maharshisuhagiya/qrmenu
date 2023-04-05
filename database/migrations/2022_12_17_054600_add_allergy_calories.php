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
        Schema::table('foods', function (Blueprint $table) {

            $table->longText('allergy')->nullable()->after('preparation_time');
            $table->longText('calories')->nullable()->after('preparation_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){

        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn('allergy');
            $table->dropColumn('calories');
        });
    }
};
