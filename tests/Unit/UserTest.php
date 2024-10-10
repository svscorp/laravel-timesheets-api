<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_many_projects()
    {
        $user = User::factory()->create();
        $projects = Project::factory()->count(3)->create();

        $user->projects()->attach($projects);

        $this->assertCount(3, $user->projects);
    }

    public function test_user_can_have_many_timesheets()
    {
        $user = User::factory()->create();
        Timesheet::factory()->count(5)->create(['user_id' => $user->id]);

        $this->assertCount(5, $user->timesheets);
    }

    public function test_deleting_user_cascades_to_timesheets()
    {
        $user = User::factory()->create();
        $timesheet = Timesheet::factory()->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('timesheets', ['id' => $timesheet->id]);
    }

    public function test_deleting_user_removes_project_associations()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $user->projects()->attach($project);

        $user->delete();

        $this->assertDatabaseMissing('project_user', [
            'user_id' => $user->id,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }
}
