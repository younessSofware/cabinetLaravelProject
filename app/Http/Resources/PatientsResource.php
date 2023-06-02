<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'id'=>$this->id,
            'attributes'=>[
                'FullName' =>$this->FullName,
                'CIN' => $this->CIN,
                'PhoneNumber' => $this->PhoneNumber,
                'Age' => $this->Age,
                'DateOfBirth' => $this->DateOfBirth,
                'Adress' => $this->Adress,
                'created_at'=>$this->created_at,
                'updated_at'=>$this->updated_at,
                'cin_image' => $this->cin_image ? asset('storage/' . $this->cin_image) : null,
                'appointement' => ['attributes' =>  $this->appointments()->get()]
               
            ],

        ];
    }
}
