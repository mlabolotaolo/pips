<?php

namespace Database\Factories;

use App\Models\RefImplementationMode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ImplementationModeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RefImplementationMode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'uuid'          => Str::uuid(),
            'name'          => $this->faker->word,
            'description'   => $this->faker->sentence,
        ];
    }
}
