<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CrudController;
use App\Models\Timesheet;

class TimesheetController extends CrudController
{

    protected function setModelClass()
    {
        $this->modelClass = Timesheet::class;
    }

    protected function getValidationRules($id = null): array
    {
        return [
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ];
    }
}
