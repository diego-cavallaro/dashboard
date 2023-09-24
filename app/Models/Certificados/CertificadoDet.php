<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoDet extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'CERTIFICADO_DET';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    use HasFactory;

    public function Certificado()
    {
       return $this->BelongsTo(Certificado::class, 'CERTIFICADO_ID', 'ID');
    }

    public function CertificadoConfigDet()
    {
       return $this->hasOne(CertificadoConfigDet::class, 'ID', 'CERTIFICADO_CONFIG_DET_ID');
    }
}