<?php

namespace Database\Factories;

use App\Models\RefGad;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RefGad::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid'          => Str::uuid(),
            'name'          => $this->faker->name,
            'description'   => ''
        ];
    }
}
