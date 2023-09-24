<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDato extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'TIPO_DATO';
    protected $primaryKey = 'ID';

    use HasFactory;
}