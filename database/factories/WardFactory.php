<?php

namespace Database\Factories;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ward>
 */
class WardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ward::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $wardTypes = [
            'MEDICAL', 'SURGICAL', 'PEDIATRIC', 'MATERNITY',
            'LABOUR', 'DELIVERY', 'ICU', 'CCU', 'ER', 'OB-GYN'
        ];

        $wardType = $this->faker->randomElement($wardTypes);

        return [
            'name' => $wardType . ' WARD ' . $this->faker->unique()->numberBetween(1, 10),
            'total_bed' => $this->faker->numberBetween(10, 50),
            'total_licensed_op_beds' => $this->faker->numberBetween(5, 40),
        ];
    }

    /**
     * Create a maternity ward
     */
    public function maternity(): Factory
    {
        return $this->state(function () {
            return [
                'name' => 'MATERNITY WARD ' . $this->faker->unique()->numberBetween(1, 5),
            ];
        });
    }

    /**
     * Create a general ward
     */
    public function general(): Factory
    {
        return $this->state(function () {
            return [
                'name' => 'GENERAL WARD ' . $this->faker->unique()->numberBetween(1, 5),
            ];
        });
    }
}
