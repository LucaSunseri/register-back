<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d') ,
            'time_start_morning' => $this->formatTime($this->time_start_morning),
            'time_end_morning' =>  $this->formatTime($this->time_end_morning),
            'time_start_afternoon' =>  $this->formatTime($this->time_start_afternoon),
            'time_end_afternoon' =>  $this->formatTime($this->time_end_afternoon),
            'signature' => $this->signature,
            'activity_id' => $this->activity_id,
        ];
    }

    private function formatTime($time) {
        if($time) {
           return Carbon::createFromFormat('H:i:s', $time)->format(('h:i'));
        }
    }
}
