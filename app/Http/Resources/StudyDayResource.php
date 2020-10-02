<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudyDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'study_week_id' => $this->study_week_id,
            'study_classes' => StudyClassResource::collection($this->classes),
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
