<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\pM]+(?:[\s\'-][\pL\pM]+)*$/u'],
            "email" => ['required', 'email', 'max:255', 'unique:users,email'],
            "password" => ['required', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return config('lantera.'.app()->getLocale().'.validation', []);
    }
}
