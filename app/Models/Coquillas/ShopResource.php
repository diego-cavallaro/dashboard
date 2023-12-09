<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopResource extends Model
{
    protected $connection = 'Visual';
    protected $table = 'SHOP_RESOURCE';
    protected $primaryKey = 'ID';
    protected $keyType = "string";

    public $incrementing = false;
    public $timestamps = false;

    use HasFactory;

    public function ShopGroups()
    {
        return $this->hasMany(ShopGroup::class('SUB_RESOURCE_ID', 'ID'));
    }
}