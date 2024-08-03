<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstimationAndCertaintyToPhases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phases', function (Blueprint $table) {
            $table->string('estimation_accuracy')->nullable();
            $table->string('certainty_of_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phases', function (Blueprint $table) {
            $table->dropColumn('estimation_accuracy');
            $table->dropColumn('certainty_of_date');
        });
    }
}
