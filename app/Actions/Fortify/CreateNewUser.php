<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Remove leading zero from phone if exists
        if (isset($input['phone']) && str_starts_with($input['phone'], '0')) {
            $input['phone'] = ltrim($input['phone'], '0');
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'country_code' => ['required', 'string', 'max:5'],
            'phone' => ['required', 'string', 'min:8', 'max:13', 'regex:/^[0-9]+$/'],
            'password' => $this->passwordRules(),
        ], [
            'name.required' => 'Full name is required.',
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
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'country_code' => $input['country_code'],
            'phone' => $input['phone'],
            'password' => $input['password'],
        ]);

        // Assign user role to new registered user
        $user->assignRole('user');

        return $user;
    }
}
