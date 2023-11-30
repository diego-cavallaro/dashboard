<?php

namespace App\Http\Requests\Coquillas;

use Illuminate\Foundation\Http\FormRequest;
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
}