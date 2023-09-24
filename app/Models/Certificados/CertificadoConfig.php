<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoConfig extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'CERTIFICADO_CONFIG';
    protected $primaryKey = 'id';

    use HasFactory;

    public function Plantilla()
    {
       return $this->BelongsTo(Plantilla::class, 'PLANTILLA_ID', 'ID');
    }

    public function CertificadoConfigDets()
    {
       return $this->hasMany(CertificadoConfigDet::class, 'CERTIFICADO_CONFIG_ID', 'ID');
    }
}
