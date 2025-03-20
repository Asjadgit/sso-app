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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->index();
            $table->string('website')->nullable();
            $table->string('phone')->nullable()->index();

            // Adding 'owner_user_id' as foriegn key
            $table->unsignedBigInteger('central_owner_user_id')->nullable()->index();
            $table->foreign('central_owner_user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('state')->nullable()->index();
            $table->string('postal_code')->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->string('industry')->nullable()->index();
            $table->unsignedInteger('no_of_employees')->nullable()->index();
            $table->unsignedSmallInteger('years_established')->nullable()->index();
            $table->decimal('annual_revenue', 15, 2)->nullable()->index();
            $table->string('tax_id', 50)->nullable()->index();
            $table->text('description')->nullable();

            // Self-referencing foreign key for hierarchical relationships
            $table->unsignedBigInteger('parent_company_id')->nullable()->index();
            $table->foreign('parent_company_id')->references('id')->on('companies')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
