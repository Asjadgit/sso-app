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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();

            // User-specific labels
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('name')->index(); // Label name
            $table->string('color')->nullable()->index(); // Optional color code for labels
            $table->string('type')->nullable();

            // Subtype for further categorization
            $table->string('subtype')->nullable()->index();

            // Description for additional context
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
