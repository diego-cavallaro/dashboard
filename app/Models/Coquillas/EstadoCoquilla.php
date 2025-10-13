<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCoquilla extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'ESTADO_COQUILLA';
    protected $primaryKey = 'ID';

    public $timestamps = false;

    use HasFactory;
}