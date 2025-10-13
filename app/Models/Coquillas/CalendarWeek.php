<?php

namespace App\Models\Coquillas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarWeek extends Model
{
    protected $connection = 'Visual';
    protected $table = 'CALENDAR_WEEK';
    protected $primaryKey = 'ROWID';

    public $timestamps = false;

    use HasFactory;
}