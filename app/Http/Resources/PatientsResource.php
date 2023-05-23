<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientsResource extends JsonResource
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
                'FullName' =>$this->FullName,
                'CIN' => $this->CIN,
                'PhoneNumber' => $this->PhoneNumber,
                'Age' => $this->Age,
                'DateOfBirth' => $this->DateOfBirth,
                'Adress' => $this->Adress,
                'Password' => $this->Password,
                'Password_Confirmation' => $this->Password_Confirmation,
                'created_at'=>$this->created_at,
                'updated_at'=>$this->updated_at,

        ],

        ];
    }
}
