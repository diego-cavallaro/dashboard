<?php

namespace App\Http\Requests\Coquillas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreGrupo extends FormRequest
{
    public function Authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return[
          'Grupo' => [
                       'required', 
                       'min:5', 
                       'max:15',
                       Rule::unique('Visual.SHOP_RESOURCE', 'ID'),
                     ],
          'Descripcion' => [
                       'max:40',
                     ],
       ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {

    //         $grupo = $validator->data->Grupo;
    //         $primerLetra = substr($grupo, 0, 1);

    //         if(strtotime($primerLetra) != 'G') {
    //             $validator->errors()->add('Grupo', 'Primer letra debe ser una G');
    //         }
    //     });
    // }

    public function after(): array
    {
        return [
            function (Validator $validator) {
              $grupo = $validator->safe()->Grupo;
              $tresLetras =  substr($grupo, 0, 3);
  
              if($tresLetras != 'GC-' & $tresLetras != 'GS-' & $tresLetras != 'GT-' & $tresLetras != 'GQ-' & $tresLetras != 'GE-'){
                // if ($this->somethingElseIsInvalid()) {
                    $validator->errors()->add(
                        'Grupo',
                        "El código de Grupo comenzar con 'GC-', 'GS-', 'GT-', 'GQ-' o 'GE-'."
                    );
                }
              if(strpos($grupo, '/'))
              {
                $validator->errors()->add(
                    'Grupo',
                    "El código de Grupo no puede contener '/'."
                );
              }
            }
        ];
    }
}