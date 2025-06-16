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
            $table->id();
            
            // Basic Employee Information
            $table->string('employee_id')->unique()->comment('Unique employee identifier');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nickname')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->text('address')->nullable();
            $table->string('passport_number')->nullable();
            
            // Legal Status Information
            $table->enum('status', [
                'work_permit_holder',
                'resident', 
                'citizen',
                'belonger'
            ])->default('work_permit_holder');
            
            // Status Document Information
            $table->enum('status_document_type', [
                'work_permit',
                'resident_permit',
                'permanent_resident_certificate',
                'naturalization_certificate',
                'botc_passport',
                'status_card'
            ])->nullable();
            
            // Work Permit Details (if applicable)
            $table->string('work_permit_card_number')->nullable();
            $table->date('work_permit_card_expiry')->nullable();
            $table->string('work_permit_first_receipt_number')->nullable();
            $table->date('work_permit_first_receipt_expiry')->nullable();
            $table->string('work_permit_second_receipt_number')->nullable();
            $table->date('work_permit_second_receipt_expiry')->nullable();
            
            // Other Document Details
            $table->string('resident_permit_number')->nullable();
            $table->date('resident_permit_expiry')->nullable();
            $table->string('permanent_resident_certificate_number')->nullable();
            $table->string('naturalization_certificate_number')->nullable();
            $table->string('botc_passport_number')->nullable();
            $table->string('status_card_number')->nullable();
            
            // Government Contributions
            $table->string('nib_number')->nullable()->comment('National Insurance Board number');
            $table->decimal('nib_deduction', 8, 2)->default(0)->comment('NIB deduction amount');
            $table->string('nhib_number')->nullable()->comment('National Health Insurance Plan number');
            $table->decimal('nhib_deduction', 8, 2)->default(0)->comment('NHIB deduction amount');
            
            // Payment Information
            $table->enum('payment_method', [
                'cibc',
                'scotiabank', 
                'rbc',
                'check'
            ])->default('check');
            $table->string('bank_account_number')->nullable();
            $table->string('bank_routing_number')->nullable();
            
            // Employment Details
            $table->date('starting_date')->nullable();
            $table->enum('contract_type', ['hourly', 'salary'])->default('hourly');
            $table->decimal('hourly_rate', 8, 2)->nullable()->comment('Hourly rate for hourly employees');
            $table->decimal('salary_amount', 10, 2)->nullable()->comment('Annual salary for salaried employees');
            $table->boolean('contract_signed')->default(false);
            $table->string('uniform_size')->nullable();
            
            // Department and Role
            $table->enum('department', ['boh', 'foh'])->default('boh')->comment('Back of House or Front of House');
            $table->string('position')->nullable();
            
            // Status and Activity
            $table->enum('employment_status', [
                'active',
                'inactive', 
                'terminated',
                'on_leave'
            ])->default('active');
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            
            // Metadata
            $table->json('additional_data')->nullable()->comment('Additional flexible data storage');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['status', 'employment_status']);
            $table->index(['department', 'employment_status']);
            $table->index(['starting_date']);
            $table->index(['employee_id']);
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
