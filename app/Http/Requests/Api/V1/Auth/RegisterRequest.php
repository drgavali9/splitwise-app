<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'   => ['required', 'min:3', 'max:10'],
            'mobile' => ['required'],
            'email'      => ['required', 'email'],
            'password'   => ['required'],
        ];
    }
}
