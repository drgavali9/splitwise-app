<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                         => $this->id,
            'name'                       => $this->name,
            'email'                      => $this->email,
            'email_verified_at'          => $this->email_verified_at,
            'password'                   => $this->password,
            'remember_token'             => $this->remember_token,
            'created_at'                 => $this->created_at,
            'updated_at'                 => $this->updated_at,
            'expenses_count'             => $this->expenses_count,
            'groups_count'               => $this->groups_count,
            'notifications_count'        => $this->notifications_count,
            'own_groups_count'           => $this->own_groups_count,
            'read_notifications_count'   => $this->read_notifications_count,
            'unread_notifications_count' => $this->unread_notifications_count,

            'expenses'  => ExpenseResource::collection($this->whenLoaded('expenses')),
            'groups'    => GroupResource::collection($this->whenLoaded('groups')),
            'ownGroups' => GroupResource::collection($this->whenLoaded('ownGroups')),
        ];
    }
}
