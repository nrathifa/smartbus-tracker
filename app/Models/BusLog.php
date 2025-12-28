<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusLog extends Model
{
    use HasFactory;

    protected $table = 'bus_logs';

    // This is the missing part that prevents data from saving!
    protected $fillable = [
        'card_uid',
        'matric_no',
        'student_name',
        'bus_id',
        'action'
    ];
}
