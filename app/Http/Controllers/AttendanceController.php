<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Requests\AttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ShowAttendanceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttendanceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $attendances = Attendance::where('user_id', $request->user()->id);
        $month = $request->query('month');
        $year = $request->query('year');

        if ($month) {
            $attendances = $attendances->whereMonth('date', $month);
        }

        if ($year) {
            $attendances = $attendances->whereYear('date', $year);
        }

        $attendances = $attendances->orderBy('date', 'desc')->get();

        return AttendanceResource::collection($attendances);
    }

    public function show($id): ShowAttendanceResource
    {
        $attendance = Attendance::find($id);

        return new ShowAttendanceResource($attendance);
    }

    public function create(AttendanceRequest $request): Response
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

    public function edit(AttendanceRequest $request, $id): Response
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

    // Funzione che controlla la data 
    private function checkDate(Request $request, ?int $id = null): ?Response
    {
        $date = $request->get('date');
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
