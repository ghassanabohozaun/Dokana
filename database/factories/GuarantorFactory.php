<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guarantor;
use App\Models\Store;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Guarantor>
 */
class GuarantorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $relationships = ['أب', 'أخ', 'صديق', 'قريب', 'ابن عم', 'جار'];
        
        return [
            'store_id' => Store::inRandomOrder()->first()->id ?? 1,
            'name' => [
                'ar' => $this->faker->name('male'),
                'en' => $this->faker->name('male'),
            ],
            'phone' => $this->faker->phoneNumber(),
            'id_number' => $this->faker->numerify('##########'),
            'address' => $this->faker->address(),
            'relationship' => $this->faker->randomElement($relationships),
            'notes' => $this->faker->sentence(),
            'status' => 1,
            'created_by' => User::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
