<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidate extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        switch($this->method()){
            case 'POST': {
                return [
                    'name'      => ['required', 'string', 'unique:users,name'],
                    'email'     => ['required', 'email', 'unique:users,email'],
                    'password'  => ['required', 'min:6']
                ];
            }
            case 'PUT': {
                return [
                    'name'      => ['required', 'digits:8', 'unique:staff,dni,'.$this->user->id],
                    'email'     => ['required', 'email', 'unique:users,email,'.$this->user->id],
                    'password'  => ['required', 'min:6']
                ];
            }
            default:break;
        }
    }

    public function messages(){
        return [
            'name.required'     => 'El campo nombres es obligatorio.',
            'name.string'       => 'El campo nombres debe ser alfanumérico.',
            'name.unique'       => 'El valor ingresado ya existe.',
            'email.required'    => 'El campo email es obligatorio.',
            'email.email'       => 'El campo email debe tener formato de correo electrónico.',
            'email.unique'      => 'El valor ingresado ya existe.',
            'password.required' => 'El campo password es obligatorio.',
            'password.min'      => 'El valor ingresado debe tener más de 6 caracteres.',
        ];
    }
}
