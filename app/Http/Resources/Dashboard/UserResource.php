<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use App\Http\Resources\Dashboard\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roleId' => $this->role_id,
            'roles' => new RoleResource($this->whenLoaded('roles')),
            'profileUrl' => $this->profile_url,
            'profilePublicId' => $this->profile_public_id,
            'phNo' => $this->ph_no,
            'dateOfBirth' => $this->date_of_birth,
            'isVerified' => $this->is_verified,
            'isPremium' => $this->is_premium,
            'isFirstTimeAppointment' => $this->is_first_time_appointment,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
