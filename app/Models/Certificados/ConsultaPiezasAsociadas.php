<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultaPiezasAsociadas extends Model
{

    protected $connection = 'ProcesoPlanta';
    protected $table = 'CONSULTA_PIEZAS_ASOCIADAS';
    protected $primaryKey = 'Pieza';

    public $timestamps = false;
    
    use HasFactory;
}
