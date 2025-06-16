<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $status = $this->faker->randomElement(['work_permit_holder', 'resident', 'citizen', 'belonger']);
        $contractType = $this->faker->randomElement(['hourly', 'salary']);
        $department = $this->faker->randomElement(['boh', 'foh']);
        
        return [
            // Basic Information
            'employee_id' => $this->faker->unique()->numerify('EMP###'),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'nickname' => $this->faker->optional(0.3)->firstName(),
            'gender' => $this->faker->optional(0.8)->randomElement(['male', 'female', 'other', 'prefer_not_to_say']),
            'date_of_birth' => $this->faker->optional(0.9)->dateTimeBetween('-65 years', '-18 years'),
            'phone_number' => $this->faker->optional(0.8)->phoneNumber(),
            'email_address' => $this->faker->optional(0.7)->email(),
            'address' => $this->faker->optional(0.8)->address(),
            'passport_number' => $this->faker->optional(0.6)->regexify('[A-Z]{2}[0-9]{6}'),
            
            // Legal Status
            'status' => $status,
            'status_document_type' => $this->getStatusDocumentType($status),
            
            // Government Contributions (only for eligible statuses)
            'nib_number' => $this->isGovernmentEligible($status) ? $this->faker->numerify('NIB######') : null,
            'nib_deduction' => $this->isGovernmentEligible($status) ? $this->faker->randomFloat(2, 20, 100) : 0,
            'nhib_number' => $this->isGovernmentEligible($status) ? $this->faker->numerify('NHIB######') : null,
            'nhib_deduction' => $this->isGovernmentEligible($status) ? $this->faker->randomFloat(2, 15, 80) : 0,
            
            // Payment Information
            'payment_method' => $this->faker->randomElement(['cibc', 'scotiabank', 'rbc', 'check']),
            'bank_account_number' => $this->faker->optional(0.7)->numerify('##########'),
            'bank_routing_number' => $this->faker->optional(0.7)->numerify('######'),
            
            // Employment Details
            'starting_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'contract_type' => $contractType,
            'hourly_rate' => $contractType === 'hourly' ? $this->faker->randomFloat(2, 12, 35) : null,
            'salary_amount' => $contractType === 'salary' ? $this->faker->randomFloat(2, 25000, 60000) : null,
            'contract_signed' => $this->faker->boolean(0.8),
            'uniform_size' => $this->faker->optional(0.7)->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            
            // Department and Role
            'department' => $department,
            'position' => $this->getPositionForDepartment($department),
            
            // Status and Activity
            'employment_status' => $this->faker->randomElement(['active', 'inactive', 'terminated', 'on_leave']),
            'termination_date' => null,
            'termination_reason' => null,
            
            // Metadata
            'additional_data' => null,
        ];
    }

    /**
     * Indicate that the employee is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => 'active',
            'termination_date' => null,
            'termination_reason' => null,
        ]);
    }

    /**
     * Indicate that the employee is terminated.
     */
    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => 'terminated',
            'termination_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'termination_reason' => $this->faker->randomElement([
                'Voluntary resignation',
                'Performance issues',
                'Redundancy',
                'End of contract',
                'Misconduct'
            ]),
        ]);
    }

    /**
     * Indicate that the employee is hourly.
     */
    public function hourly(): static
    {
        return $this->state(fn (array $attributes) => [
            'contract_type' => 'hourly',
            'hourly_rate' => $this->faker->randomFloat(2, 12, 35),
            'salary_amount' => null,
        ]);
    }

    /**
     * Indicate that the employee is salaried.
     */
    public function salaried(): static
    {
        return $this->state(fn (array $attributes) => [
            'contract_type' => 'salary',
            'hourly_rate' => null,
            'salary_amount' => $this->faker->randomFloat(2, 25000, 60000),
        ]);
    }

    /**
     * Indicate that the employee works in Back of House.
     */
    public function backOfHouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'department' => 'boh',
            'position' => $this->faker->randomElement([
                'Line Cook',
                'Prep Cook',
                'Dishwasher',
                'Kitchen Manager',
                'Sous Chef',
                'Head Chef'
            ]),
        ]);
    }

    /**
     * Indicate that the employee works in Front of House.
     */
    public function frontOfHouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'department' => 'foh',
            'position' => $this->faker->randomElement([
                'Server',
                'Host/Hostess',
                'Bartender',
                'Cashier',
                'Shift Supervisor',
                'Assistant Manager',
                'General Manager'
            ]),
        ]);
    }

    /**
     * Indicate that the employee is a TC citizen.
     */
    public function citizen(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'citizen',
            'status_document_type' => 'botc_passport',
            'botc_passport_number' => $this->faker->regexify('[A-Z]{2}[0-9]{6}'),
            'nib_number' => $this->faker->numerify('NIB######'),
            'nhib_number' => $this->faker->numerify('NHIB######'),
            'nib_deduction' => $this->faker->randomFloat(2, 20, 100),
            'nhib_deduction' => $this->faker->randomFloat(2, 15, 80),
        ]);
    }

    /**
     * Indicate that the employee is a belonger.
     */
    public function belonger(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'belonger',
            'status_document_type' => 'status_card',
            'status_card_number' => $this->faker->numerify('SC######'),
            'nib_number' => $this->faker->numerify('NIB######'),
            'nhib_number' => $this->faker->numerify('NHIB######'),
            'nib_deduction' => $this->faker->randomFloat(2, 20, 100),
            'nhib_deduction' => $this->faker->randomFloat(2, 15, 80),
        ]);
    }

    /**
     * Indicate that the employee is a work permit holder.
     */
    public function workPermitHolder(): static
    {
        return $this->state(function (array $attributes) {
            $hasCard = $this->faker->boolean(0.7);
            $hasFirstReceipt = !$hasCard && $this->faker->boolean(0.8);
            $hasSecondReceipt = !$hasCard && !$hasFirstReceipt;

            $state = [
                'status' => 'work_permit_holder',
                'status_document_type' => 'work_permit',
                'nib_number' => null,
                'nhib_number' => null,
                'nib_deduction' => 0,
                'nhib_deduction' => 0,
            ];

            if ($hasCard) {
                $state['work_permit_card_number'] = $this->faker->numerify('WP######');
                $state['work_permit_card_expiry'] = $this->faker->dateTimeBetween('now', '+2 years');
            } elseif ($hasFirstReceipt) {
                $state['work_permit_first_receipt_number'] = $this->faker->numerify('WPR1######');
                $state['work_permit_first_receipt_expiry'] = $this->faker->dateTimeBetween('now', '+6 months');
            } elseif ($hasSecondReceipt) {
                $state['work_permit_second_receipt_number'] = $this->faker->numerify('WPR2######');
                $state['work_permit_second_receipt_expiry'] = $this->faker->dateTimeBetween('now', '+6 months');
            }

            return $state;
        });
    }

    /**
     * Indicate that the employee has an expiring work permit.
     */
    public function expiringWorkPermit(): static
    {
        return $this->workPermitHolder()->state(fn (array $attributes) => [
            'work_permit_card_number' => $this->faker->numerify('WP######'),
            'work_permit_card_expiry' => $this->faker->dateTimeBetween('now', '+30 days'),
        ]);
    }

    /**
     * Indicate that the employee is a resident.
     */
    public function resident(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resident',
            'status_document_type' => 'resident_permit',
            'resident_permit_number' => $this->faker->numerify('RP######'),
            'resident_permit_expiry' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
            'nib_number' => $this->faker->numerify('NIB######'),
            'nhib_number' => $this->faker->numerify('NHIB######'),
            'nib_deduction' => $this->faker->randomFloat(2, 20, 100),
            'nhib_deduction' => $this->faker->randomFloat(2, 15, 80),
        ]);
    }

    /**
     * Get appropriate status document type for a given status.
     */
    private function getStatusDocumentType(string $status): ?string
    {
        return match ($status) {
            'work_permit_holder' => 'work_permit',
            'resident' => 'resident_permit',
            'citizen' => $this->faker->randomElement(['botc_passport', 'naturalization_certificate']),
            'belonger' => 'status_card',
            default => null,
        };
    }

    /**
     * Check if status is eligible for government contributions.
     */
    private function isGovernmentEligible(string $status): bool
    {
        return in_array($status, ['citizen', 'belonger', 'resident']);
    }

    /**
     * Get appropriate position for department.
     */
    private function getPositionForDepartment(string $department): string
    {
        $positions = [
            'boh' => ['Line Cook', 'Prep Cook', 'Dishwasher', 'Kitchen Manager', 'Sous Chef', 'Head Chef'],
            'foh' => ['Server', 'Host/Hostess', 'Bartender', 'Cashier', 'Shift Supervisor', 'Assistant Manager', 'General Manager'],
        ];

        return $this->faker->randomElement($positions[$department] ?? ['Employee']);
    }
}
