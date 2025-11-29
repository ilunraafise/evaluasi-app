<?php

use App\Exports\HasilEvaluasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\EvaluationController;

/*
|--------------------------------------------------------------------------|
| Public Routes (Peserta)                                                   |
|--------------------------------------------------------------------------|
*/

Route::get('/', function () {
    return view('welcome');
});

// Peserta mengisi form
Route::get('/form/{form}/take', [EvaluationController::class, 'take'])->name('form.take');
Route::post('/form/{form}/submit', [EvaluationController::class, 'submit'])->name('form.submit');
Route::get('/form/{form}/already-submitted', [EvaluationController::class, 'alreadySubmitted'])->name('already_submitted');

Route::post('/forms/{form}/questions/import', [QuestionController::class, 'import'])->name('forms.questions.import');

Route::get('/export/excel/{id}', function($id) {
    $form = App\Models\Form::findOrFail($id);
    return Excel::download(new HasilEvaluasiExport($form), 'hasil_evaluasi.xlsx');
})->name('export.hasil');


/*
|--------------------------------------------------------------------------|
| Admin Panel                                                               |
|--------------------------------------------------------------------------|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('forms', FormController::class);

    Route::get('/forms/{form}/results', [FormController::class, 'results'])->name('forms.results');

    Route::get('/forms/{form}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/forms/{form}/questions', [QuestionController::class, 'store'])->name('forms.questions.store');
    Route::delete('/forms/{form}/questions/{question}', [QuestionController::class, 'destroy'])->name('forms.questions.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
