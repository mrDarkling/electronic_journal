<?php

namespace App\Http\Resources;

use App\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'roles' => $this->isAdmin() ? User::ROLE_ADMIN : User::ROLE_USER,
        ];
    }
}
