<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CrudController;
use App\Models\Project;

class ProjectController extends CrudController
{
    protected function setModelClass()
    {
        $this->modelClass = Project::class;
    }

    protected function getValidationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:active,inactive,completed',
        ];
    }
}
