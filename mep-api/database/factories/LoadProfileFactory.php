<?php

namespace Database\Factories;

use App\Models\LoadProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoadProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LoadProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'base_load' => 25,
            'comprehensive_load' => 25,
            'description' => $this->faker->text(80),
            'full_time_employees' => (mt_rand(2000, 5000) / 100),
            'local_load' => 25,
            'name' => $this->faker->name(),
            'organisation_load' => 25
        ];
    }
}
