<?php

namespace Database\Factories;

use App\Models\Timesheet;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimesheetFactory extends Factory
{
    protected $model = Timesheet::class;

    public function definition()
    {
        return [
            'task_name' => $this->faker->sentence(4),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'hours' => $this->faker->randomFloat(2, 0.5, 8),
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
        ];
    }
}
