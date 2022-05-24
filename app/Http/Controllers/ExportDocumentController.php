<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportDocumentController extends Controller
{
    public function exportWord(Request $request) {

        $user = User::find($request->user_id);

        $attendances = Attendance::where('user_id', $user->id)
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->get();  
        
        $nameFile = "{$request->user()->id}{$user->name}-{$request->month}-{$request->year}";

        $templateProcessor = new TemplateProcessor('word-template/template.docx');

        $templateProcessor->setValue('name', $user->name);
        $templateProcessor->setValue('surname', $user->surname);
        $templateProcessor->setValue('month', $this->setMonth($request->month));
        $templateProcessor->setValue('year', $request->year);

        foreach ($attendances as $attendance) {
            $templateProcessor->setValue('activity' . $attendance->date->format('d'), $attendance->activity->type);
            $templateProcessor->setValue('sam' . $attendance->date->format('d'), $this->formatTime($attendance->time_start_morning));
            $templateProcessor->setValue('eam' . $attendance->date->format('d'), $this->formatTime($attendance->time_end_morning));
            $templateProcessor->setValue('spm' . $attendance->date->format('d'), $this->formatTime($attendance->time_start_afternoon));
            $templateProcessor->setValue('epm' . $attendance->date->format('d'), $this->formatTime($attendance->time_end_afternoon));
            $templateProcessor->setImageValue('signature' . $attendance->date->format('d'), $user->signature);
            $templateProcessor->setImageValue('signatureMain' . $attendance->date->format('d'), $request->user()->signature);
        }
        for($i = 0; $i < 32; $i++) {
            $templateProcessor->setValue('activity' . $i, '');
            $templateProcessor->setValue('sam' . $i, '');
            $templateProcessor->setValue('eam' . $i, '');
            $templateProcessor->setValue('spm' . $i, '');
            $templateProcessor->setValue('epm' . $i, '');
            $templateProcessor->setValue('signature' . $i, '');
            $templateProcessor->setValue('signatureMain' . $i, '');
        }
        $templateProcessor->saveAs($nameFile . '.docx');

        return response()->download("$nameFile.docx")->deleteFileAfterSend(true);
    }

    private function formatTime($time) {
        if($time) {
           return Carbon::createFromFormat('H:i:s', $time)->format(('h:i'));
        }
    }

    private function setMonth($month) {
        $mesi = [
            'Gennaio',
            'Febbraio',
            'Marzo',
            'Aprile',
            'Maggio',
            'Giugno',
            'Luglio',
            'Agosto',
            'Settempre',
            'Ottobre',
            'Novembre',
            'Dicembre',
        ];

        return $mesi[$month - 1];

        // $dateObj   = DateTime::createFromFormat('!m', $month);
        // return $dateObj->format('F'); // March
    }
}
