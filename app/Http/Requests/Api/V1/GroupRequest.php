<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }

    public function persist(array $validateData): Group
    {
        $validateData['owner_id'] = auth()->id();

        return Group::create($validateData);
    }
}
