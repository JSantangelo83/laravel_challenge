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
        Schema::create('tvshows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('release_date');
            $table->integer('rating');
            $table->string('genre');
            $table->unsignedBigInteger('director_id');
            $table->foreign('director_id')
                    ->references('id')
                    ->on('directors');
            $table->unsignedBigInteger('age_classification_id');
            $table->foreign('age_classification_id')
                    ->references('id')
                    ->on('age_classifications');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvshows');
    }
};
