<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Requests\AttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ShowAttendanceResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $isNotSuperAdminOrTutor = $this->checkisNotSuperAdminOrTutor($request);

        $attendanceQuery = Attendance::query();
        if($isNotSuperAdminOrTutor){
            $attendanceQuery = $attendanceQuery->where('user_id', $request->user()->id);
        }

        $user = $request->query('user');
        $month = $request->query('month');
        $year = $request->query('year');

        if ($month) {
            $attendanceQuery = $attendanceQuery->whereMonth('date', $month);
        }

        if ($year) {
            $attendanceQuery = $attendanceQuery->whereYear('date', $year);
        }

        if ($user) {
            $userNameFilters = explode(" ", $user);
            $attendanceQuery = $attendanceQuery->whereHas('user', function(Builder $query) use($userNameFilters){
                foreach($userNameFilters as $word){
                    $query = $query->where(function (Builder $subquery) use($word){
                        $subquery->where('surname', 'like', '%' . $word . '%')
                        ->orWhere('name', 'like', '%' . $word . '%');
                    });
                }
            });
        }

        $attendanceQuery = $attendanceQuery->orderBy('date', 'desc')->get();

        if($isNotSuperAdminOrTutor){
            return AttendanceResource::collection($attendanceQuery);
        }
        return AttendanceResource::collection($attendanceQuery->sortBy('user.surname'));
    }

    public function show($id): ShowAttendanceResource
    {
        $attendance = Attendance::find($id);

        return new ShowAttendanceResource($attendance);
    }

    public function create(AttendanceRequest $request): Response
    {
        $isNotSuperAdminOrTutor = $this->checkisNotSuperAdminOrTutor($request);

        $new_attendece = new Attendance();

        $checkdate = $this->checkDate($request);

        if (!is_null($checkdate)) {
            return $checkdate;
        }

        $new_attendece->fill($request->all());

        if($isNotSuperAdminOrTutor) {
            $new_attendece->user_id = $request->user()->id;  
        }
        
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

    // Funzione che controlla se non è un Tutor o un Super Admin
    private function checkisNotSuperAdminOrTutor($request) {
        return $request->user()->roles
        ->whereNotIn('name', ['super-admin', 'tutor'])
        ->count() > 0;
    }
}
