<?php

namespace App\Http\Requests\Coquillas;

use Illuminate\Foundation\Http\FormRequest;
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
          'Coquilla' => ['required', 'min:5', 'max:15'],
          'Fecha' => 'required',
       ];
    }
}