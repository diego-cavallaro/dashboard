<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DurezaDespacho extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'DUREZA_DESPACHO';
    protected $primaryKey = 'pieza';

    use HasFactory;
}