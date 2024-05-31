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
            'id'          => $this->id,
            'description' => $this->description,
            'amount'      => $this->amount,
            'split_type'  => $this->split_type,
            'group_id'     => $this->group_id,
            'paid_user_id' => $this->paid_user_id,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,

            'group' => new GroupResource($this->whenLoaded('group')),
            'paid_user'      => new GroupResource($this->whenLoaded('paidUser')),
            'split_expenses' => ExpenseSplitResource::collection($this->whenLoaded('expenseSplits')),
        ];
    }
}
