<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MounthRequest extends FormRequest
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
        return [
            'symbol' => 'required|in:FA,FB,FC,FD,FE',
            'name' => 'required|string',
            'quantity' => 'required|string',
            'state' => 'required|integer|between:1,4',
            'brick' => 'required|string',
            'mark' => 'nullable|boolean',
            'fire' => 'nullable|boolean',
            'discription' => 'nullable|string',
        ];
    }
}
