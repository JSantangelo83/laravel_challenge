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
        Schema::create('actors_tvshows', function (Blueprint $table) {
            $table->unsignedBigInteger('actor_id');
            $table->unsignedBigInteger('tvshow_id');
            $table->foreign('actor_id')
                    ->references('id')
                    ->on('actors')
                    ->onDelete('cascade');

            $table->foreign('tvshow_id')
                    ->references('id')
                    ->on('tvshows')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actors_tvshows');
    }
};
