<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;
    //protected $connection = 'mysql2';

    public function GetRouteKeyName()
    {
        return 'url';
    }

    public function Doc()
    {
        return $this->belongsToMany(doc::class);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name']= $name;
        $this->attributes['url'] = str::slug($name);
    }
}