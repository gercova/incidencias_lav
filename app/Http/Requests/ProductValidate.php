<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ProductValidate extends FormRequest {
    
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'category_id'   => ['required'],
            'description'   => ['required', 'string', 'unique:products,description'],
            'detail'        => ['required', 'string'],
            'cantidad'      => ['required', 'int', 'min:0'],
        ];
    }

    public function messages(){
        return [
            'category_id.required'  => 'El campo descripción es obligatorio.',
            'description.required'  => 'El campo descripción es obligatorio.',
            'description.string'    => 'El campo descripción debe ser alfanumérico.',
            'description.unique'    => 'El campo descripción ya existe.',
            'cantidad.required'     => 'El campo cantidad es obligatorio.',
            'cantidad.int'          => 'El valor ingresado debe ser intero.',
            'cantidad.min'          => 'El valor ingresado no debe ser menor a 0'
        ];
    }
}
