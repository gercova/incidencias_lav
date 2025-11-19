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
            'created_at'            => 'date|nullable',
            'incidenceCategoryId'   => 'required',
            'typeIncidenceId'       => 'required',
            'description'           => 'required',
            'solution'              => 'required',
        ];
    }

    public function messages(){
        return [
            'staffId.required'              => 'El campo usuario es obligatorio.',
            'created_at.date'               => 'El campo fecha debe ser de tipo fecha (YYYY-MM-DD)',
            'incidenceCategoryId.required'  => 'El campo categoría incidencia es obligatorio.',
            'typeIncidenceId.required'      => 'El campo tipo categoria es obligatorio.',
            'description.required'          => 'El campo descripción es obligatorio.',
            'solution.required'             => 'El campo solución es requerida obligatorio.'
        ];
    }

}
