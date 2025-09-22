<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationValidate extends FormRequest {
    
    public function authorize() {
        return true;
    }

    public function rules() {
        switch($this->method()){
            case 'POST': {
                return [
                    'name'          => ['required', 'string', 'unique:applications,name'],
                    'description'   => ['required', 'string'],
                ];
            }
            case 'PUT': {
                return [                    
                    'name'          => ['required', 'string', 'unique:applications,name,'.$this->application->id],
                    'description'   => ['required', 'string'],
                ];
            }
            default:break;
        }
    }

    public function messages() {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string'   => 'El campo nombre debe ser alfanumérico.',
            'name.unique'   => 'El valor ingresado ya existe.',
            'description'   => 'El campo descripción es obligatorio.',
            'description'   => 'El campo descripción debe ser alfanumérico.',
        ];
    }
}
