<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'CERTIFICADO';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    use HasFactory;

    public function CertificadoConfigDet()
    {
       return $this->hasOne(CertificadoConfigDet::class, 'CERTIFICADO_CONFIG_DET_ID', 'ID');
    }

    public function Hr()
    {
       return $this->hasOne(Hr::class, 'PIEZA', 'PIEZA');
    }

    public function CertificadoDets()
    {
       return $this->hasMany(CertificadoDet::class, 'CERTIFICADO_ID', 'ID');
    }

    public function CertificadoConfig()
    {
       return $this->hasOne(CertificadoConfig::class, 'ID', 'CERTIFICADO_CONFIG_ID');
    }
}
