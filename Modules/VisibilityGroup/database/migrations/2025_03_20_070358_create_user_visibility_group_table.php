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
        Schema::create('user_visibility_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('central_user_id');
            $table->foreignId('visibility_group_id')->constrained()->onDelete('cascade');

            $table->foreign('central_user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_visibility_group');
    }
};
