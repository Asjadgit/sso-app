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
        Schema::create('template_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type')->index();
            $table->string('view_type')->index();
            $table->unsignedBigInteger('central_admin_id')->nullable();
            $table->unsignedBigInteger('central_user_id')->nullable();
            $table->json('configuration');
            $table->boolean('is_default');
            $table->timestamps();

            $table->foreign('central_admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('central_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_configurations');
    }
};
