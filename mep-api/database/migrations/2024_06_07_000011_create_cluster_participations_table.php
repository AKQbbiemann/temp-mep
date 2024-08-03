<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClusterParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cluster_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cluster_id')->constrained()->onDelete('cascade');
            $table->string('cluster_name');
            $table->foreignId('profile_id')->constrained('load_profiles', 'id')->onDelete('cascade');
            $table->string('profile_name');
            $table->integer('competence_id')->nullable();
            $table->string('competence_name')->nullable();
            $table->integer('load');
            $table->integer('requirement_id');
            $table->foreignId('phase_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('cluster_participations');
    }
}
