<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Group */
class GroupResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'owner_id' => $this->owner_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //            'users_count' => $this->users_count,

            'owner' => new UserResource($this->whenLoaded('owner')),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
