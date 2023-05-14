<?php

namespace Database\Factories;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $password = $this->faker->password;
        return [
            'FullName' => $this->faker->name(),
            'CIN' => $this->faker->unique()->bothify('??######'),
            'PhoneNumber' => $this->faker->phoneNumber,
            'Age' => $this->faker->numberBetween(1,100),
            'DateOfBirth' => $this->faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'Adress' => $this->faker->address,
            'Password' => $password,
            'Password_Confirmation' => $password
        ];
    }
}

