<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'group' => $this->group,
            'student_name' => $this->student_name,
            'record_book' => $this->record_book,
        ];
    }
}
