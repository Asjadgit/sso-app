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
        Schema::create('custom_filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('central_user_id'); // Reference to the user who created the filter
            $table->string('name'); // Name of the filter
            $table->string('type'); // Type (e.g., contacts, companies, deals)
            $table->json('filter_criteria'); // JSON object containing filter data
            $table->timestamps();

            $table->foreign('central_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_filters');
    }
};
