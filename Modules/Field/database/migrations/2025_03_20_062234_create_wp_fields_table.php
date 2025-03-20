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
        Schema::create('wp_fields', function (Blueprint $table) {
            $table->id();  // Primary key

            $table->unsignedBigInteger('form_id');  // Form this field belongs to
            // Add columns for source tracking
            $table->unsignedBigInteger('source_id')->nullable(); // ID of the source
            $table->enum('source', ['wp_form', 'crm', 'csv', 'api', 'scraping'])->default('crm'); // Origin of the field

            $table->unsignedBigInteger('wp_field_id')->nullable()->index();  // WordPress field ID (for linking with WP forms)

            $table->text('label')->nullable();  // Field label
            // Add the 'name' column
            $table->string('name')->nullable();  // You can specify the position if needed

            $table->string('type')->nullable();  // Field type (e.g., text, radio, checkbox)
            $table->enum('field_type', ['contact', 'company'])
                ->nullable()
                ->default(null)
                ->comment('Determines if the field is a contact or company field');

            $table->boolean('required')->default(false);  // Is the field required?
            $table->string('default_value')->nullable();  // Default value for the field (if any)
            $table->string('placeholder')->nullable();  // Placeholder text for input fields
            $table->string('validation_rules')->nullable();  // Custom validation rules (if any)
            $table->integer('position')->default(0);  // Position of the field in the form
            $table->text('help_text')->nullable();  // Help text for the field

            // File upload related columns
            $table->string('allowed_file_types')->nullable();  // Allowed file types for file upload fields
            $table->integer('max_file_size')->nullable();  // Max file size for file upload fields (in KB)

            // Conditional logic
            $table->json('conditional_logic')->nullable();  // Store conditional logic (e.g., field visibility rules)

            // Additional field restrictions for number/text fields
            $table->integer('min_value')->nullable();  // Minimum value for number fields
            $table->integer('max_value')->nullable();  // Maximum value for number fields
            $table->integer('max_length')->nullable();  // Max length for text/textarea fields

            // Icon or image for the field (optional)
            $table->string('icon_url')->nullable();  // URL of an icon or image associated with the field

            // Calculation-related columns
            $table->boolean('calculation_is_enabled')->default(false);  // Whether the calculation is enabled
            $table->text('calculation_code')->nullable();  // Raw calculation code (e.g., "$F21")
            $table->text('calculation_code_php')->nullable();  // PHP code for calculation
            $table->text('calculation_code_js')->nullable();  // JavaScript code for calculation



            // Add indexes for better query performance
            $table->index('source');

            $table->timestamps();

            // Add foreign key constraint for form relationship
            $table->foreign('form_id')->references('id')->on('wp_forms')->onDelete('cascade');

            // Index for faster lookups
            $table->index('form_id');  // Index to optimize queries filtering by form_id

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wp_fields');
    }
};
