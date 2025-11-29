<?php

namespace App\Exports;

use App\Models\Form;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HasilEvaluasiExport implements FromArray, WithHeadings
{
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function headings(): array
    {
        $questions = $this->form->questions()->get();

        $header = ['Nama', 'Sekolah', 'Wilayah', 'Jenjang', 'Batch'];

        foreach ($questions as $q) {
            $header[] = $q->question;
        }

        return $header;
    }

    public function array(): array
    {
        $questions   = $this->form->questions()->get();
        $answers     = $this->form->answers()->with('respondent')->get();
        $respondents = $answers->pluck('respondent')->unique('id');

        $exportData = [];

        foreach ($respondents as $res) {

            if (!$res) continue; // jika ada jawaban tanpa responden

            $row = [
                $res->name ?? 'Anonymous',
                $res->origin_school,
                $res->region,
                $res->level,
                $res->batch,
            ];

            foreach ($questions as $q) {
                $ans = $answers
                    ->where('question_id', $q->id)
                    ->where('respondent_id', $res->id)
                    ->first();

                $row[] = $q->type === 'checkbox'
                            ? implode(', ', json_decode($ans->answer ?? '[]', true) ?? [])
                            : ($ans->answer ?? '-');
            }

            $exportData[] = $row;
        }

        return $exportData;
    }
}
