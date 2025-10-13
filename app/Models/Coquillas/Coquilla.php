<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coquilla extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'COQUILLA';
    protected $primaryKey = 'RESOURCE_ID';
    protected $keyType = "string";
    
    public $incrementing = false;
    public $timestamps = false;

    use HasFactory;

    public function EstadoCoquilla()
    {
       return $this->hasOne(EstadoCoquilla::class, 'ID', 'ESTADO_COQUILLA_ID');
    }

    public function ShopResource()
    {
       return $this->hasOne(ShopResource::class, 'ID', 'RESOURCE_ID');
    }
}