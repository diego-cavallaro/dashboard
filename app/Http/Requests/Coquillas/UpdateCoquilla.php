<?php

namespace App\Http\Requests\Coquillas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;


class UpdateCoquilla extends FormRequest
{
    public function Authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return[
          'Fecha' => 'required',
          'EstadoCoquilla' => 'required',
          'Observaciones' => 'max:200',
       ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
              $estadoCoquilla = $validator->safe()->EstadoCoquilla;
  
              if($estadoCoquilla === "0")
              {
                $validator->errors()->add(
                    'EstadoCoquilla',
                    "Se debe seleccionar un Estado de Coquilla."
                );
              }
            }
        ];
    }
}