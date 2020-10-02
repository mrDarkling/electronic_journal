<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StudyWeekResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'         => $this->id,
            'is_even'    => $this->is_even,
            'owner_id'   => $this->owner_id,
            'days'       => StudyDayResource::collection($this->days),
            //created_at' => $this->created_at->format('d.m.Y')
            'created_at' => $this->created_at,
        ];
    }
}
