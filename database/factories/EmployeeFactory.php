<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Governorate;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = ['male', 'female'];
        $material_status = ['single', 'married', 'divorced', 'widowed'];
        $currency = ['ILS', 'USD', 'GBP'];

        return [
            'first_name' => [
                'en' => fake()->firstName,
                'ar' => fake()->lastName,
            ],

            'father_name' => [
                'en' => fake()->firstName,
                'ar' => fake()->lastName,
            ],
            'grand_father_name' => [
                'en' => fake()->firstName,
                'ar' => fake()->lastName,
            ],

            'family_name' => [
                'en' => fake()->firstName,
                'ar' => fake()->lastName,
            ],

            'password' => '',
            'personal_id' => fake()->randomNumber(9),
            'gender' => fake()->randomElement($gender),
            'birthday' => fake()->date,
            'marital_status' => fake()->randomElement($material_status),
            'mobile_no' => mt_rand(1000000000, 9999999999),
            'alternative_mobile_no' => mt_rand(1000000000, 9999999999),
            'email' => fake()->email,
            'governoate_id' => Governorate::inRandomOrder()->first()->id,
            'city_id' => City::inRandomOrder()->first()->id,
            'address_details' => fake()->sentence(10),
            'bank_name' => fake()->sentence(3),
            'iban' => fake()->randomNumber(5),
            'banck_account' => fake()->randomNumber(5),
            'basic_salary' => fake()->randomNumber(3),
            'currency' => 'USD',
            'photo' => '',
        ];
    }
}
