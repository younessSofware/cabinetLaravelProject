<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>(string)$this->id,
            'attributes'=>[
                'reason' =>$this->reason,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'created_at'=>$this->created_at,
                'updated_at'=>$this->updated_at,
                'status'=>$this->status
            ],
        ];
    }
}
