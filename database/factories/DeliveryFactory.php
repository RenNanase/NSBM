<?php

namespace Database\Factories;

use App\Models\Delivery;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Delivery::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ward_id' => Ward::factory(),
            'user_id' => User::factory(),
            'report_date' => $this->faker->date(),
            'svd' => $this->faker->numberBetween(0, 10),
            'lscs' => $this->faker->numberBetween(0, 5),
            'vacuum' => $this->faker->numberBetween(0, 3),
            'forceps' => $this->faker->numberBetween(0, 2),
            'breech' => $this->faker->numberBetween(0, 2),
            'eclampsia' => $this->faker->numberBetween(0, 1),
            'twin' => $this->faker->numberBetween(0, 1),
            'mrp' => $this->faker->numberBetween(0, 2),
            'fsb_mbs' => $this->faker->numberBetween(0, 1),
            'bba' => $this->faker->numberBetween(0, 1),
            'notes' => $this->faker->optional()->paragraph(),
            // total is calculated automatically in the model's boot method
        ];
    }
}
