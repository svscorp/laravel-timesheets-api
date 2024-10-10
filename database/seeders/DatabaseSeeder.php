<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Timesheet;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Users
        $users = User::factory()->count(10)->create();

        // Create Projects
        $projects = Project::factory()->count(5)->create();

        // Assign Users to Projects
        $projects->each(function ($project) use ($users) {
            $project->users()->attach(
                $users->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        // Create Timesheets
        $users->each(function ($user) use ($projects) {
            Timesheet::factory()->count(rand(1, 5))->create([
                'user_id' => $user->id,
                'project_id' => $projects->random()->id,
            ]);
        });
    }
}
