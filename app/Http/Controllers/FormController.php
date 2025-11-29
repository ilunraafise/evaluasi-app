<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Question;
use App\Models\Answer;

class FormController extends Controller
{
    // List semua form
    public function index()
    {
        $forms = Form::latest()->get();
        return view('forms.index', compact('forms'));
    }

    // Form buat baru
    public function create()
    {
        return view('forms.create');
    }

    public function show(Form $form)
    {
        $questions = $form->questions()->get();
        return view('forms.show', compact('form', 'questions'));
    }


    public function store(Request $request)
    {
        $request->validate(['title' => 'required', 'description' => 'nullable']);

        Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('forms.index')->with('success', 'Form berhasil dibuat.');
    }

    public function edit(Form $form)
    {
        return view('forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $request->validate(['title' => 'required', 'description' => 'nullable']);

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('forms.index')->with('success', 'Form berhasil diperbarui.');
    }

    public function destroy(Form $form)
    {
        $form->delete();
        return redirect()->back()->with('success', 'Form berhasil dihapus.');
    }

    // Lihat hasil jawaban peserta
    public function results(Form $form)
    {
        $answers = Answer::with(['respondent', 'question'])
            ->where('form_id', $form->id)
            ->get();

        return view('forms.results', compact('form', 'answers'));
    }
}
