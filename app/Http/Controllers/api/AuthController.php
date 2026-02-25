<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'status' => 'pending',
            'verification_token' => Str::random(64),
        ]);

        $user->notify(new VerifyEmailNotification($user->verification_token));

        return response()->json([
            'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản',
            'user' => new UserResource($user),
        ]);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $token = $request->validated()['token'];

        $user = User::where('verification_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Token không hợp lệ hoặc đã hết hạn'
            ], 400);
        }

        if ($user->email_verified_at !== null) {
            return response()->json([
                'message' => 'Email đã được xác thực trước đó'
            ], 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'Xác thực email thành công'
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credential = $request->validated();

        if (!Auth::attempt($credential)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không hợp lệ.'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return response()->json([
                'message' => 'Vui lòng xác thực email trước khi đăng nhập.'
            ], 403);
        }

        if ($user->status === 'banned') {
            Auth::logout();
            return response()->json([
                'message' => 'Tài khoản của bạn đã bị cấm.'
            ], 403);
        }

        $user->update(['last_login_at' => now()]);

        // Token hết hạn sau 7 ngày
        $token = $user->createToken('auth_token', ['*'], now()->addDays(7))->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();
        $token->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
}
