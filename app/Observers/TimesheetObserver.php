<?php

namespace App\Observers;

use App\Models\Timesheet;
use App\Models\User;

class TimesheetObserver
{
    public function created(Timesheet $timesheet)
    {
        $timesheet->user->projects()->syncWithoutDetaching([$timesheet->project_id]);
    }

    public function updated(Timesheet $timesheet)
    {
        if ($timesheet->wasChanged('user_id') || $timesheet->wasChanged('project_id')) {
            // Remove old association if it exists and there are no other timesheets
            $oldUserId = $timesheet->getOriginal('user_id');
            $oldProjectId = $timesheet->getOriginal('project_id');
            if ($oldUserId && $oldProjectId) {
                $oldUser = User::find($oldUserId);
                if ($oldUser && !$oldUser->timesheets()->where('project_id', $oldProjectId)->exists()) {
                    $oldUser->projects()->detach($oldProjectId);
                }
            }

            // Add new association
            $timesheet->user->projects()->syncWithoutDetaching([$timesheet->project_id]);
        }
    }
}
