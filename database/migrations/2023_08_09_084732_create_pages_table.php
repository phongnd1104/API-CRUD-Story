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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_id');
            $table->unsignedBigInteger('image_id');
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');

            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
