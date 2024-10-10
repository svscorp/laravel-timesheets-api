<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_have_many_users()
    {
        $project = Project::factory()->create();
        $users = User::factory()->count(3)->create();

        $project->users()->attach($users);

        $this->assertCount(3, $project->users);
    }

    public function test_project_can_have_many_timesheets()
    {
        $project = Project::factory()->create();
        Timesheet::factory()->count(5)->create(['project_id' => $project->id]);

        $this->assertCount(5, $project->timesheets);
    }

    public function test_deleting_project_cascades_to_timesheets()
    {
        $project = Project::factory()->create();
        $timesheet = Timesheet::factory()->create(['project_id' => $project->id]);

        $project->delete();

        $this->assertDatabaseMissing('timesheets', ['id' => $timesheet->id]);
    }

    public function test_deleting_project_removes_user_associations()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $project->users()->attach($user);

        $project->delete();

        $this->assertDatabaseMissing('project_user', [
            'user_id' => $user->id,
            'project_id' => $project->id
        ]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
