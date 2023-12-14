<?php

namespace App\Http\Requests\Coquillas;

use App\Models\Coquillas\EstadoCoquilla;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreCoquilla extends FormRequest
{
    public function Authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return[
          'Coquilla' => ['required', 
                         'min:5', 
                        'max:15',
                         Rule::unique('Visual.SHOP_RESOURCE', 'ID'),
                        ],
          'Fecha' => 'required',
          'EstadoCoquilla' => 'required',
          'Observaciones' => 'max:200',
       ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
              $grupo = $validator->safe()->Coquilla;
              $estadoCoquilla = $validator->safe()->EstadoCoquilla;
              $primerLetra =  substr($grupo, 0, 1);
  
              if($primerLetra !== 'W'){
                // if ($this->somethingElseIsInvalid()) {
                    $validator->errors()->add(
                        'Coquilla',
                        "El código de Coquilla debe comenzar con 'W'."
                    );
                }
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