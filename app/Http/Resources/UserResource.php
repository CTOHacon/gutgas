<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        $returnUser = parent::toArray($request);

        $userAllPermissions = $this->getAllPermissions()->pluck('name')->toArray();
        $returnUser['permissions'] = $userAllPermissions;

        $userAllRoles = $this->getRoleNames()->toArray();
        $returnUser['roles'] = $userAllRoles;

        return $returnUser;
    }
}
