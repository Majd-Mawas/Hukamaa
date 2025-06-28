<?php

namespace Modules\PatientManagement\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChronicConditionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\PatientManagement\App\Models\ChronicCondition::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true)
        ];
    }
}
