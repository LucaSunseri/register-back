<?php

namespace App\Models;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
