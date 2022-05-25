<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function getAll() {
        $activities = Activity::all();

        return response($activities);
    }

    public function create(Request $request) {

        $new_activity = new Activity();
        $new_activity->fill($request->all());
        $new_activity->save();

        return response()->json($new_activity);
    }

    public function edit(Request $request, $id) {

        $activity = Activity::findOrFail($id);
        $activity->update($request->all());
        $activity->refresh();

        return response()->json($activity);
    }

    public function delete() {

        return response('soft delite');
    }
}
