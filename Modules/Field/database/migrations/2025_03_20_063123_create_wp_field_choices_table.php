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
        Schema::create('wp_field_choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id');  // foreign key to the fields table
            $table->text('label');
            $table->text('value')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_style')->nullable();
            $table->timestamps();

            $table->foreign('field_id')->references('id')->on('wp_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wp_field_choices');
    }
};
