<?php

namespace Database\Factories;

use App\Models\ProjectDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

class DescriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectDescription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->paragraph,
        ];
    }
}
