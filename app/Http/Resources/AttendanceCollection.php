<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendanceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'date' => $this->date->format('d-m-Y') ,
            'time_start_morning' => $this->time_start_morning->format('h:i'),
            'time_end_morning' => $this->time_end_morning,
            'time_start_afternoon' => $this->time_start_afternoon,
            'time_end_afternoon' => $this->time_end_afternoon,
            'signature' => $this->signature,
            'activity_id' => $this->activity_id,
        ];
    }
}
