<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'CUSTOMER';
    protected $primaryKey = 'ID';

    use HasFactory;

}