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
        Schema::create('contact_user_follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('central_owner_user_id');
            $table->unsignedBigInteger('contact_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('central_owner_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_user_follows');
    }
};
