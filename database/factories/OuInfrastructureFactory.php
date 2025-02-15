<?php

namespace Database\Factories;

use App\Models\RefOperatingUnit;
use App\Models\OuInfrastructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class OuInfrastructureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OuInfrastructure::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'ou_id' => RefOperatingUnit::all()->random()->id,
            'y2016' => $this->faker->randomFloat() * 1000000,
            'y2017' => $this->faker->randomFloat() * 1000000,
            'y2018' => $this->faker->randomFloat() * 1000000,
            'y2019' => $this->faker->randomFloat() * 1000000,
            'y2020' => $this->faker->randomFloat() * 1000000,
            'y2021' => $this->faker->randomFloat() * 1000000,
            'y2022' => $this->faker->randomFloat() * 1000000,
            'y2023' => $this->faker->randomFloat() * 1000000,
            'y2024' => $this->faker->randomFloat() * 1000000,
            'y2025' => $this->faker->randomFloat() * 1000000,
        ];
    }
}
