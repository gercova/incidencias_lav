<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffValidate extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        switch($this->method()){
            case 'POST': {
                return [
                    'dni'                   => ['required', 'digits:8', 'unique:staff,dni'],
                    'names'                 => ['required'],
                    'society'               => ['required', 'numeric'],
                    'area'                  => ['required'],
                    'position_description'  => ['required'],
                    'direction'             => ['required'],
                    'organizational_unit'   => ['required'],
                    'start_date'            => ['required', 'date'],
                ];
            }
            case 'PUT': {
                return [
                    'dni'                   => ['required', 'digits:8', 'unique:staff,dni,'.$this->staff->id],
                    'names'                 => ['required'],
                    'society'               => ['required', 'numeric'],
                    'area'                  => ['required'],
                    'position_description'  => ['required'],
                    'direction'             => ['required'],
                    'organizational_unit'   => ['required'],
                    'start_date'            => ['required', 'date'],
                ];
            }
            default:break;
        }
    }

    public function messages(){
        return [
            'dni.required'                  => 'El campo dni es obligatorio.',
            'dni.digits'                    => 'El campo dni debe tener exactamente 8 dígitos.',
            'dni.unique'                    => 'El valor ingresado ya existe.',
            'names.required'                => 'El campo nombres es obligatorio.',
            'society.required'              => 'El campo sociedad es obligatorio.',
            'society.numeric'               => 'El campo debe ser númerico.',
            'area.required'                 => 'El campo área es obligatorio.',
            'position_description.required' => 'El campo posición es obligatorio.',
            'organizational_unit.required'  => 'El campo unidad organizacional es obligatorio.',
            'start_date.required'           => 'El campo fecha inicio es obligatorio.',
            'start_date.date'               => 'El campo fecha inicio tiene que ser del tipo fecha.',
        ];
    }
}
