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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            // Adding 'owner_user_id' foreign key
            $table->unsignedBigInteger('central_owner_user_id')->nullable();
            $table->foreign('central_owner_user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->string('name')->nullable()->index();
            $table->string('first_name')->nullable()->index();
            $table->string('last_name')->nullable()->index();

            // Adding 'referred_by' and 'source_contact_id' Self-referencing foreign keys
            $table->foreignId('referred_by')->nullable()->constrained('contacts')->onDelete('set null');
            $table->foreignId('source_contact_id')->nullable()->constrained('contacts')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
