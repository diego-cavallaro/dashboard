<?php

namespace App\Models\Certificados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $connection = 'ProcesoPlanta';
    protected $table = 'CUSTOMER_ORDER';
    protected $primaryKey = 'ID';

    use HasFactory;

    public function Customer()
    {
       return $this->hasOne(Customer::class, 'ID', 'CUSTOMER_ID');
    }
}