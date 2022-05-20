<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportDocumentController extends Controller
{
    public function exportWord(Request $request) {

        $attendances = Attendance::where('user_id', $request->user()->id)
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->get();  
        
        $nameFile = $request->user()->id . $request->user()->name . '-' . $request->month . '-' . $request->year;

        $templateProcessor = new TemplateProcessor('word-template/template.docx');

        $templateProcessor->setValue('name', $request->user()->name);
        $templateProcessor->setValue('surname', $request->user()->surname);
        $templateProcessor->setValue('month', $request->month);
        $templateProcessor->setValue('year', $request->year);

        foreach ($attendances as $attendance) {
            $templateProcessor->setValue('activity' . $attendance->date->format('d'), $attendance->activity->type);
            $templateProcessor->setValue('sam' . $attendance->date->format('d'), $this->formatTime($attendance->time_start_morning));
            $templateProcessor->setValue('eam' . $attendance->date->format('d'), $this->formatTime($attendance->time_end_morning));
            $templateProcessor->setValue('spm' . $attendance->date->format('d'), $this->formatTime($attendance->time_start_afternoon));
            $templateProcessor->setValue('epm' . $attendance->date->format('d'), $this->formatTime($attendance->time_end_afternoon));
            $templateProcessor->setImageValue('signature' . $attendance->date->format('d'), $request->user()->signature);
        }
        for($i = 0; $i < 32; $i++) {
            $templateProcessor->setValue('activity' . $i, '');
            $templateProcessor->setValue('sam' . $i, '');
            $templateProcessor->setValue('eam' . $i, '');
            $templateProcessor->setValue('spm' . $i, '');
            $templateProcessor->setValue('epm' . $i, '');
            $templateProcessor->setValue('signature' . $i, '');
        }
        $templateProcessor->saveAs($nameFile . '.docx');

        return response()->download($nameFile . '.docx')->deleteFileAfterSend(true);
    }

    private function formatTime($time) {
        if($time) {
           return Carbon::createFromFormat('H:i:s', $time)->format(('h:i'));
        }
    }
}
