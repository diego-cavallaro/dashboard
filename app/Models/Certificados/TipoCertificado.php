<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCertificado extends Model
{
    protected $connection = 'TipoCertificado';
    protected $table = 'TIPO_CERTIFICADO';
    protected $primaryKey = 'ID';

    use HasFactory;
}