<?php

namespace App\Enums;
use Illuminate\Support\Facades\DB;

enum IncidenceStatus: int {

    public function getIncidenceType(){
        return $it = DB::table('incidence_type')->select('id')->get();
    }

    case LEVE       = 1;
    case MODERADO   = 2;
    case GRAVE      = 3;
    case CRITICO    = 4;

    public function color(): string {
        return match ($this) {
            IncidenceStatus::LEVE       => 'success',
            IncidenceStatus::MODERADO   => 'primary',
            IncidenceStatus::GRAVE      => 'warning',
            IncidenceStatus::CRITICO    => 'danger'
        };
    }
}