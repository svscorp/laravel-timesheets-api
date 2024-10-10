<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CrudController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends CrudController
{

    protected function setModelClass()
    {
        $this->modelClass = User::class;
    }

    protected function getValidationRules($id = null): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
        ];
    }

    // This method is overwritten, because it has password hashing
    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = $this->modelClass::create($validatedData);

        return response()->json($user, 201);
    }

    // This method is overwritten, because it has password hashing
    public function update(Request $request, $id)
    {
        $user = $this->getModel()::findOrFail($id);
        $validatedData = $this->validateRequest($request, $id);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json($user);
    }
}
