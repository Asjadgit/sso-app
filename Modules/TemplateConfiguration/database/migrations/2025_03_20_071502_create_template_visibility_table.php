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
        Schema::create('template_visibility', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_configuration_id');
            $table->unsignedBigInteger('visibility_level_id');
            $table->timestamps();

            $table->foreign('template_configuration_id')->references('id')->on('template_configurations')->onDelete('cascade');
            $table->foreign('visibility_level_id')->references('id')->on('visibility_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_visibility');
    }
};
