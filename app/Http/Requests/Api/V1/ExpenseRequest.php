<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group_id'     => ['required', 'exists:groups,id'],
            'paid_user_id' => ['required', 'exists:users,id'],
            'description'  => ['nullable'],
            'amount'       => ['required', 'numeric'],
            'split_type'   => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
