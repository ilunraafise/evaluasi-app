<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;


class QuestionController extends Controller
{
    // Menampilkan form tambah banyak pertanyaan
    public function create(Form $form)
    {
        return view('questions.create_multiple', compact('form'));
    }

    public function import(Request $request, Form $form)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new QuestionsImport($form), $request->file('excel_file'));

        return redirect()->route('forms.show', $form)->with('success', 'Pertanyaan berhasil diimpor dari Excel!');
    }

    // Simpan semua pertanyaan sekaligus
    public function store(Request $request, Form $form)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.options' => 'nullable|array',
        ]);

        foreach ($request->questions as $q) {
            \App\Models\Question::create([
                'form_id' => $form->id,
                'question' => $q['question'],
                'type' => $q['type'],
                'options' => isset($q['options']) ? json_encode($q['options']) : null,
            ]);
        }

        return redirect()->route('forms.show', $form)->with('success', 'Semua pertanyaan berhasil disimpan.');
    }
}
