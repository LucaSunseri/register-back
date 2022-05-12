<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use App\Http\Requests\AttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttendanceController extends Controller
{
    public function create(AttendanceRequest $request)
    {

        $new_attendece = new Attendance();

        $checkdate = $this->checkDate($request);

        if (!is_null($checkdate)) {
            return $checkdate;
        }

        $new_attendece->fill($request->all());
        $new_attendece->user_id = $request->user()->id;
        $new_attendece->save();

        return response($new_attendece);
    }

    public function edit(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $checkdate = $this->checkDate($request, $id);

        if (!is_null($checkdate)) {
            return $checkdate;
        }

        $attendance->update($request->all());
        $attendance->refresh();

        return response($attendance);
    }

    public function show()
    {
        $attendeces = Attendance::where('user_id', auth('sanctum')->user()->id)->get();

        return response($attendeces);
    }

    // Funzione che controlla la data 
    private function checkDate(Request $request, ?int $id = null): ?Response
    {
        $date = Carbon::createFromFormat("d-m-Y", $request->get('date'));
        $attendanceQuery = Attendance::where('user_id', $request->user()->id)
            ->whereDate('date', $date);
        if (!is_null($id)) {
            $attendanceQuery = $attendanceQuery->where('id', '<>', $id);
        }

        $attendanceQuery = $attendanceQuery->exists();

        if ($attendanceQuery) {
            return response('Esiste già un campo per questa data', 422);
        }

        if ($date > Carbon::now()) {
            return response('Non sei nel futuro!', 422);
        }
        return null;
    }
}
