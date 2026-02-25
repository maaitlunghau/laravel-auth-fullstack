<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'role' => 'required|in:user,admin',
            'status' => 'nullable|in:active,pending,banned',
            'avatar' => 'nullable|url|max:255',
            'google_id' => 'nullable|string|max:255|unique:users,google_id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng không được để trống',
            'email.required' => 'Email người dùng không được để trống',
            'email.email' => 'Email người dùng không đúng định dạng',
            'email.unique' => 'Email của bạn đã tồn tại trong hệ thống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp với mật khẩu',
            'role.in' => 'Vai trò người dùng phải là user hoặc admin',
            'status.in' => 'Trạng thái người dùng phải là active, pending hoặc banned',
            'avatar.url' => 'Avatar phải là URL hợp lệ',
            'google_id.unique' => 'Google ID đã được sử dụng',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
