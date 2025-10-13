<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarChange extends Model
{
    protected $connection = 'Visual';
    protected $table = 'CALENDAR_CHANGE';
    protected $primaryKey = 'ROWID';

    public $timestamps = false;

    use HasFactory;
}