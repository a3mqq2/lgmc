<?php

namespace Database\Factories;

use App\Models\MedicalFacilityType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalFacility>
 */
class MedicalFacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'serial_number' => $this->faker->unique()->numerify('SN####'),
            'name' => $this->faker->company,
            'medical_facility_type_id' => MedicalFacilityType::factory(),
            'address' => $this->faker->address,
            'phone_number' => $this->faker->numerify('##########'),
            'commerical_number' => $this->faker->unique()->bothify('C##??'),
            'activity_start_date' => $this->faker->date(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
