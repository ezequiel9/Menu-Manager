<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'nullable|string',
            'meal_size' => 'required|string',
            'phone' => 'nullable|string',
            'room_number' => 'required|string',
            'diet' => 'nullable|string',
            'floor_id' => 'nullable|integer|exists:floors,id'
        ]);
        if (empty($request->email)) {
            $request->email = Str::slug($request->name).$request->room_number.'@menu.com';
        }
        $user = User::query()->create($request->all());
        return response()->json($user, 200);
    }
}
