<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tvshows_episodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('season_id');
            $table->foreign('season_id')
                    ->references('id')
                    ->on('tvshows_seasons')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('director_id');
            $table->foreign('director_id')
                        ->references('id')
                        ->on('directors');
            $table->integer('episode_number');
            $table->string('title');
            $table->date('air_date');
            $table->text('synopsis');
            $table->time('length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvshows_episodes');
    }
};
