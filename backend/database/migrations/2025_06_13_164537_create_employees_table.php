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
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_id')->unique()->nullable(false);
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('nickname')->nullable();
            $table->string('gender')->nullable(); // ENUM: Male, Female, Other
            $table->date('dob')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_address')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('status')->nullable(false); // ENUM: Work Permit Holder, Resident, Citizen, Belonger
            $table->string('nib_number')->nullable();
            $table->boolean('nib_deduction_override')->default(false);
            $table->string('nhib_number')->nullable();
            $table->boolean('nhib_deduction_override')->default(false);
            $table->string('payment_method')->nullable(false); // ENUM: CIBC, Scotiabank, RBC, Check
            $table->date('starting_date')->nullable(false);
            $table->string('contract_type')->nullable(false); // ENUM: Hourly, Salary
            $table->decimal('rate', 10, 2)->nullable(false);
            $table->boolean('contract_signed')->nullable(false);
            $table->string('uniform_size')->nullable();
            $table->uuid('department_id')->nullable();
            $table->uuid('job_role_id')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('job_role_id')->references('id')->on('job_roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
