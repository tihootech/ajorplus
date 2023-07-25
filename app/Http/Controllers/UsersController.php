<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UsersController extends Controller
{

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return ['success' => true, 'user' => $user];
    }

    public function update(User $user, UserRequest $request)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        return ['success' => true, 'user' => $user];
    }

    public function destroy(User $user)
    {
        $user->delete();
        return ['success' => true];
    }
}
