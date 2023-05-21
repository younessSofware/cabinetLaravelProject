<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientsWithLastAppointmentResource extends PatientsResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lastAppointment = $this->appointments->last();

        return array_merge(parent::toArray($request), [
            'appointments' => $lastAppointment ? new AppointmentResource($lastAppointment) : 'null',
        ]);
    }
}
