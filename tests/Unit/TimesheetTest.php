<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimesheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_timesheet_belongs_to_user()
    {
        $user = User::factory()->create();
        $timesheet = Timesheet::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $timesheet->user->id);
    }

    public function test_timesheet_belongs_to_project()
    {
        $project = Project::factory()->create();
        $timesheet = Timesheet::factory()->create(['project_id' => $project->id]);

        $this->assertEquals($project->id, $timesheet->project->id);
    }

    public function test_creating_timesheet_associates_user_with_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Timesheet::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id
        ]);

        $this->assertTrue($user->projects->contains($project));
    }
}
