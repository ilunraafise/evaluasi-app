<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Answer;
use App\Models\Respondent;
use Illuminate\Http\Request;
use App\Exports\HasilEvaluasiExport;
use Maatwebsite\Excel\Facades\Excel;

class EvaluationController extends Controller
{
    // Halaman peserta mengisi form
    public function take(Form $form)
    {
        $questions = $form->questions()->get();
        return view('evaluation.take', compact('form', 'questions'));
    }

    // Submit jawaban peserta
    public function submit(Request $request, Form $form)
    {
        // 1️⃣ Simpan identitas peserta
        $respondent = Respondent::create([
            'form_id' => $form->id,
            'name' => $request->respondent_name,
            'origin_school' => $request->origin_school,
            'level' => $request->level,
            'region' => $request->region,
            'batch' => $request->batch,
        ]);

        // 2️⃣ Simpan jawaban
        foreach ($request->answers as $questionId => $answer) {
            Answer::create([
                'form_id' => $form->id,
                'question_id' => $questionId,
                'respondent_id' => $respondent->id,
                'answer' => $answer,
            ]);
        }

        // Redirect ke halaman terima kasih
        return redirect()->route('already_submitted', $form->id);
    }

    // Halaman terima kasih
    public function alreadySubmitted(Form $form)
    {
        return view('evaluation.already_submitted', compact('form'));
    }

    public function export(Form $form)
    {
        return Excel::download(new HasilEvaluasiExport($form), 'hasil_evaluasi.xlsx');
    }
}
