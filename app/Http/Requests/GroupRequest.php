<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'     => ['required'],
            'owner_id' => ['required', 'exists:users'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
