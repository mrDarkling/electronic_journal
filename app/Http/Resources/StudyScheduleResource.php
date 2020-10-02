<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudyScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'groups' => $this->groups,
            'day' => $this->day,
            'created_at' => $this->created_at,
        ];
    }
}
