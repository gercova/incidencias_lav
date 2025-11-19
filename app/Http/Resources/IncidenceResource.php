<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncidenceResource extends JsonResource
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
            'staff_id' => $this->id,
            'incidenceCategoryId' => $this->incidenceCategoryId,
            'typeIncidenceId' => $this->typeIncidenceId,
            'description' => $this->description,
            'solution' => $this->solution,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
