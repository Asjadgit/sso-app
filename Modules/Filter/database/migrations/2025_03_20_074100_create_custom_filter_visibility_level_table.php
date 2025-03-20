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
        Schema::create('custom_filter_visibility_level', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_filter_id')->constrained()->onDelete('cascade');
            $table->foreignId('visibility_level_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_filter_visibility_level');
    }
};
