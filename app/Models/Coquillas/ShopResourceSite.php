<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopResourceSite extends Model
{
    protected $connection = 'Visual';
    protected $table = 'SHOP_RESOURCE_SITE';
    protected $primaryKey = 'ROWID';
    protected $keyType = "string";

    public $incrementing = false;
    public $timestamps = false;

    use HasFactory;

    public function ShopResource()
    {
        return $this->hasOne(ShopResource::class('RESOURCE_ID', 'ID'));
    }
}