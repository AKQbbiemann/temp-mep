<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('dates_are_strict')->default(false);
            $table->text('description');
            $table->string('probability');
            $table->string('state');
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
        Schema::dropIfExists('phases');
    }
}
