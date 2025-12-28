<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'model',
        'capacity',
        'route',
        'status',
        'driver_name',
        'driver_contact',
        'current_passengers',
        'latitude',
        'longitude',
    ];
}
