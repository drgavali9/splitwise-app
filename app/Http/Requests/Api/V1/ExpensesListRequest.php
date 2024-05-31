<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'int', 'exists:groups,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


}
