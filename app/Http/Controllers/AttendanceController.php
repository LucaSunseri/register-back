<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use App\Http\Requests\AttendanceRequest;

class AttendanceController extends Controller
{
    public function create(AttendanceRequest $request)
    {

        $new_attendece = new Attendance();
        $new_attendece->fill($request->all());
        $new_attendece->user_id = auth('sanctum')->user()->id;

        // Controllo data esistente e data attuale 
        $attendeces = Attendance::where('user_id', auth('sanctum')->user()->id)->get();

        // dd($new_attendece->date);

        if ($attendeces->contains('date', $new_attendece->date)) {
            return response('Esiste già un campo per questa data', 201);
        } else if ($new_attendece->date > Carbon::now()) {
            return response('Non sei nel futuro!', 201);
        }

        $new_attendece->save();

        $response = [
            'attendece' => $new_attendece
        ];

        return response($response, 201);
    }

    public function edit(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = Carbon::createFromFormat("d-m-Y", $request->get('date'));
        $attendanceExists = Attendance::where('user_id', $request->user()->id)
            ->where('id', '<>', $id)
            ->whereDate('date', $date)
            ->exists();
        if ($attendanceExists) {
            return response('Esiste già un campo per questa data', 422);
        }
        if ($date > Carbon::now()) {
            return response('Non sei nel futuro!', 422);
        }
        $attendance->update($request->all());
        $attendance->refresh();
        return response($attendance);
    }

    public function show()
    {
        $attendeces = Attendance::where('user_id', auth('sanctum')->user()->id)->get();

        return response()->json(compact('attendeces'));
    }
}
