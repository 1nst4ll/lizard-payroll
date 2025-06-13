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
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('business_id');
            $table->string('payroll_period'); // ENUM: Weekly, Bi-weekly, Monthly
            $table->string('week_run_from'); // ENUM: Monday to Sunday
            $table->boolean('deduct_breaks');
            $table->string('break_threshold')->nullable(); // INTERVAL
            $table->string('break_duration')->nullable(); // INTERVAL
            $table->boolean('pay_overtime');
            $table->string('overtime_threshold')->nullable(); // INTERVAL
            $table->decimal('overtime_ratio', 8, 2)->nullable();
            $table->boolean('pay_public_holiday');
            $table->decimal('public_holiday_ratio', 8, 2)->nullable();
            $table->boolean('record_lieu_days');
            $table->boolean('pay_sick_days');
            $table->integer('sick_days_paid_per_year')->nullable();
            $table->boolean('deduct_days_for_salary_employee');
            $table->integer('expected_number_of_days')->nullable();
            $table->boolean('deduct_nib');
            $table->boolean('deduct_nhib');
            $table->boolean('add_tips');
            $table->boolean('add_service_charge');
            $table->decimal('service_charge_distribution_percentage', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('business_settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_settings');
    }
};
