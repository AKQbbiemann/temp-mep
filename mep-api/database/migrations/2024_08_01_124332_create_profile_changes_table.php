<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_profile_id')->constrained('load_profiles');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->float('fte_change')->nullable();
            $table->integer('local_load')->nullable();
            $table->integer('organisation_load')->nullable();
            $table->integer('comprehensive_load')->nullable();
            $table->integer('base_load')->nullable();
            $table->text('reason');
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
        Schema::dropIfExists('profile_changes');
    }
}
