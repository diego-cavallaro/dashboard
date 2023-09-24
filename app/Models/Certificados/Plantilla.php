<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'PLANTILLA';
    protected $primaryKey = 'ID';

    use HasFactory;

    public function CertificadoConfigs()
    {
       return $this->hasMany(CertificadoConfig::class, 'PLANTILLA_ID', 'ID');
    }

    public function TipoCertificado()
    {
       return $this->hasOne(TipoCertificado::class, 'TIPO_CERTIFICADO_ID', 'ID');
    }
}
