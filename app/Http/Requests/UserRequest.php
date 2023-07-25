<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->user->id ?? 0;
        return [
            'name' => 'required',
            'email' => "required|unique:users,email,$userId",
            'role' => 'required|in:admin,viewer',
            'password' => [Rule::requiredIf(!$userId), 'string'],
        ];
    }
}
