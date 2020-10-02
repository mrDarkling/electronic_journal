<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudyClassResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'study_day_id' => $this->study_day_id,
            'group_id'     => $this->group_id,
            'start_time'   => $this->start_time,
            'end_time'     => $this->end_time,
            'group'        => StudentGroupResource::make($this->group),
            'study_subject_id' => $this->study_subject_id,
            'subject'      => StudySubjectResource::make($this->subject),
        ];
    }
}
