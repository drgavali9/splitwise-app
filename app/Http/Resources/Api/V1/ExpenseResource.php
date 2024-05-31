<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Expense */
class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'id'          => $this->id,
            'description' => $this->description,
            'amount'      => $this->amount,
            'split_type'  => $this->split_type,

            'group_id'     => $this->group_id,
            'paid_user_id' => $this->paid_user_id,

            'group' => new GroupResource($this->whenLoaded('group')),
        ];
    }
}
