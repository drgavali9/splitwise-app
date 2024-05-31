<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ExpenseSplit */
class ExpenseSplitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'amount'          => $this->amount,
            'expense_id'      => $this->expense_id,
            'group_id'        => $this->group_id,
            'paid_user_id'    => $this->paid_user_id,
            'receive_user_id' => $this->receive_user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'expense'  => new ExpenseResource($this->whenLoaded('expense')),
            'group'    => new GroupResource($this->whenLoaded('group')),
            'paidUser' => new UserResource($this->whenLoaded('paidUser')),
            'receiveUser' => new UserResource($this->whenLoaded('receiveUser')),
        ];
    }
}
