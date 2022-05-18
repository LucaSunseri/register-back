<?php

namespace App\Http\Controllers;

use App\Models\Activity;

class ActivityController extends Controller
{
    public function getAll() {
        $activities = Activity::all();

        return response($activities);
    }
}
