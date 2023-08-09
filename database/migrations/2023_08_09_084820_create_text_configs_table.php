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
        Schema::create('text_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('positions');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('text_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('text_id')->references('id')->on('texts')->onDelete('cascade');
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_configs');
    }
};
