<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEmployeeChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('employee_changes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('employee_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_profile_id')->constrained('load_profiles');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->float('change');
            $table->text('reason');
            $table->timestamps();
        });
    }
}
