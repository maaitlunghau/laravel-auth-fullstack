<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('id', 'desc')->paginate(10);
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);

        return response(new UserResource($user), 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response(new UserResource($user));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    public function ban(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Không thể cấm tài khoản admin.'
            ], 403);
        }

        if ($user->status === 'banned') {
            return response()->json([
                'message' => 'Người dùng này đã bị cấm rồi.'
            ], 400);
        }

        $user->update(['status' => 'banned']);

        return response()->json([
            'message' => 'Đã cấm người dùng thành công.',
            'data' => new UserResource($user)
        ]);
    }

    public function unban(User $user)
    {
        if ($user->status !== 'banned') {
            return response()->json([
                'message' => 'Người dùng này chưa bị cấm.'
            ], 400);
        }

        $user->update(['status' => 'active']);

        return response()->json([
            'message' => 'Huỷ bỏ cấm người dùng thành công.',
            'data' => new UserResource($user)
        ]);
    }
}
