<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hr extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'HR';
    protected $primaryKey = 'pieza';

    use HasFactory;

    public function CustomerOrder()
    {
       return $this->hasOne(CustomerOrder::class, 'ID', 'OF_FSC');
    }

    public function Certificados()
    {
       return $this->hasMany(Certificado::class, 'PIEZA', 'PIEZA');
    }

    public function CertificadoConfigs()
    {
        return $this->hasMany(CertificadoConfig::class, 'PART_ID', 'CODIGO');
    }
}