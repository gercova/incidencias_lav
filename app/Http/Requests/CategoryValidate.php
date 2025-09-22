<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryValidate extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'description' => ['required', 'string', 'unique:incidence_category,description'],
            'aka' => ['required|string|unique:incidence_category,aka'],
        ];
    }

    public function messages(){
        return [
            'description.required'  => 'El campo descripción es obligatorio.',
            'description.string'    => 'El campo descripción debe ser alfanumérico.',
            'description.unique'    => 'El campo descripción ya existe.',
            'aka.required'          => 'El campo aka es obligatorio.',
            'aka.unique'            => 'El campo aka ya existe.',
        
        ];
    }
}
