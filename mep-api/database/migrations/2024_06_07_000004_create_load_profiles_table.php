<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('cluster_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->integer('comprehensive_load');
            $table->integer('base_load');
            $table->integer('organisation_load');
            $table->integer('local_load');
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
        Schema::dropIfExists('load_profiles');
    }
}
