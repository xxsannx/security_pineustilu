<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country_code' => ['required', 'string', 'max:5'],
            'phone' => ['required', 'string', 'min:8', 'max:13', 'regex:/^[0-9]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.string' => 'Name must be text.',
            'name.max' => 'Name maximum 255 characters.',
            
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'Email is already registered.',
            
            'country_code.required' => 'Country code is required.',
            
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number minimum 8 digits.',
            'phone.max' => 'Phone number maximum 13 digits.',
            'phone.regex' => 'Phone number can only contain numbers.',
            
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password minimum 8 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove leading zero from phone if exists
        if ($this->phone && str_starts_with($this->phone, '0')) {
            $this->merge([
                'phone' => ltrim($this->phone, '0')
            ]);
        }
    }
}
