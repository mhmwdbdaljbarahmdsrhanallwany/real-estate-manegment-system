<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class CreateNewUser
{
    use PasswordValidationRules;
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => [
                'required',
                'string',
                'max:100'
            ],
            'last_name' => [
                'required',
                'string',
                'max:100'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();
        $user = User::create([
            // الاسم للعرض فقط داخل users
            'name' => $input['first_name'].' '.$input['last_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        $user->profile()->create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'status' => 'Active',
        ]);
        return $user;
    }
}