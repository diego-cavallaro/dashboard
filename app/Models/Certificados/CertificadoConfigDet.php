<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoConfigDet extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'CERTIFICADO_CONFIG_DET';
    //protected $table = 'users';
    protected $primaryKey = 'ID';

    use HasFactory;

    public function CertificadoConfig()
    {
       return $this->BelongsTo(CertificadoConfig::class, 'CERTIFICADO_CONFIG_ID', 'ID');
    }

    public function TipoDato()
    {
       return $this->hasOne(TipoDato::class, 'ID', 'TIPO_DATO_ID');
    }
}