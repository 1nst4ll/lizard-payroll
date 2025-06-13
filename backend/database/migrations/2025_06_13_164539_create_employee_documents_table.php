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
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->string('document_type')->nullable(false); // ENUM: Work Permit, Resident Permit, etc.
            $table->string('card_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('first_receipt_number')->nullable();
            $table->date('first_receipt_exp_date')->nullable();
            $table->string('second_receipt_number')->nullable();
            $table->date('second_receipt_exp_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
