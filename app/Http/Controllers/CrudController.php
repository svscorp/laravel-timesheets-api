<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class CrudController
{
    protected $modelClass;

    public function __construct()
    {
        $this->setModelClass();
    }

    abstract protected function setModelClass();

    protected function getModel(): Model
    {
        return new $this->modelClass;
    }

    public function index(Request $request)
    {
        $query = $this->modelClass::query();

        $this->applyFilters($request, $query);

        $perPage = $request->input('per_page', 5);
        $items = $query->paginate($perPage);

        return response()->json($items->toArray());
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);
        $item = $this->modelClass::create($validatedData);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = $this->modelClass::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = $this->modelClass::findOrFail($id);
        $validatedData = $this->validateRequest($request, $id, true);
        $item->update($validatedData);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = $this->modelClass::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Record deleted successfully']);
    }

    protected function applyFilters(Request $request, $query)
    {
        $fillable = $this->getModel()->getFillable();
        $filters = $request->only($fillable);

        // Iterate over each filter and apply the corresponding query condition
        collect($filters)->each(function ($value, $field) use ($query) {
            // Fields containing 'name'
            if (Str::contains($field, 'name')) {
                $query->where($field, 'LIKE', '%' . $value . '%');
                return;
            }

            // Specific date range fields
            if ($field === 'start_date') {
                $query->whereDate($field, '>=', $value);
                return;
            }

            if ($field === 'end_date') {
                $query->whereDate($field, '<=', $value);
                return;
            }

            // Other date fields (assuming fields ending with '_date' or named 'birthdate')
            if (Str::endsWith($field, '_date') || Str::contains($field, 'date')) {
                $query->whereDate($field, '=', $value);
                return;
            }

            // All other fields
            $query->where($field, '=', $value);
        });
    }

    protected function validateRequest(Request $request, $id = null, $isUpdate = false)
    {
        $rules = $this->getValidationRules($id);

        if ($isUpdate) {
            $rules = array_intersect_key($rules, $request->all());

            // Make all rules optional for update
            $rules = array_map(function($rule) {
                return str_replace('required|', '', $rule);
            }, $rules);
        }

        return $request->validate($rules);
    }

    abstract protected function getValidationRules($id = null): array;
}
