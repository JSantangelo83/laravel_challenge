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
        Schema::create('tvshows_seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tvshow_id');
            $table->foreign('tvshow_id')
                    ->references('id')
                    ->on('tvshows')
                    ->onDelete('cascade');
            $table->integer('season_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvshows_seasons');
    }
};
