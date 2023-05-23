<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
             return [
                 'patient_CIN' => Patient::all()->random()->CIN,
                 'start_time' => $this->faker->dateTimeBetween('now', '+1 week'),
                 'end_time' => $this->faker->dateTimeBetween('+1 hour', '+1 week'),
                 'reason' => $this->faker->sentence(),
             ];

    }
}
