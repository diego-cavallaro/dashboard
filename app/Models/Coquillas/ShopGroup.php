<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopGroup extends Model
{
    protected $connection = 'Visual';
    protected $table = 'SHOP_GROUP';
    protected $primaryKey = 'ROWID';

    public $incrementing = false;
    public $timestamps = false;

    use HasFactory;

    public function GroupResource()
    {
        return $this->hasOne(ShopResource::class, 'ID', 'GROUP_RESOURCE_ID');
    }

    public function ShopResource()
    {
        return $this->hasOne(ShopResource::class, 'ID', 'SUB_RESOURCE_ID');
    }
}