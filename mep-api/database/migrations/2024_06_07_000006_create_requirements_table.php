<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->foreignId('owner')->nullable()->constrained('users');
            $table->foreignId('creator')->nullable()->constrained('users');
            $table->string('customer')->nullable();
            $table->text('description');
            $table->text('infos')->nullable();
            $table->string('probability');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('start_date_is_strict')->default(false);
            $table->boolean('end_date_is_strict')->default(false);
            $table->text('time_period_description')->nullable();
            $table->string('state');
            $table->integer('company_priority')->nullable();
            $table->text('company_priority_description')->nullable();
            $table->integer('requested_priority')->nullable();
            $table->text('requested_priority_description');
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
        Schema::dropIfExists('requirements');
    }
}
