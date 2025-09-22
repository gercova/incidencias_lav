<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncidenceValidate extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'staffId'               => 'required',
            'incidenceCategoryId'   => 'required',
            'typeIncidenceId'       => 'required',
            'description'           => 'required',
            'solution'              => 'required',
        ];
    }

    public function messages(){
        return [
            'staffId.required'              => 'El campo usuario es obligatorio.',
            'incidenceCategoryId.required'  => 'El campo categoría incidencia es obligatorio.',
            'typeIncidenceId.required'      => 'El campo tipo categoria es obligatorio.',
            'description.required'          => 'El campo descripción es obligatorio.',
            'solution.required'             => 'El campo solución es requerida obligatorio.'
        ];
    }

}
