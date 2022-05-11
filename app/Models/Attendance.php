<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'date',
        'time_start_morning',
        'time_end_morning',
        'time_start_afternoon',
        'time_end_afternoon',
        'signature',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
