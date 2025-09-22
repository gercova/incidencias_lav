<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray($request){
        return [
            'id'    => $this->id,
            'text'  => $this->names,
            'unit'  => $this->organizational_unit,
        ];
    }
}
