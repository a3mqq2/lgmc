<?php

namespace Database\Factories;

use App\Models\MedicalFacilityType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalFacilityTypeFactory extends Factory
{
    protected $model = MedicalFacilityType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'en_name' => $this->faker->word,
        ];
    }
}
