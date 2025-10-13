<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piezas extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'PIEZAS';
    protected $primaryKey = 'pieza';

    use HasFactory;
}