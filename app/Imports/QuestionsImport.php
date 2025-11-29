<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class QuestionsImport implements ToCollection
{
    protected $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function collection(Collection $rows)
    {
        // Lewati header
        $rows->shift();

        foreach ($rows as $row) {
            $questionText = $row[0];
            $options = array_filter(array_slice($row->toArray(), 1)); // Opsi dimulai kolom ke-2
            Question::create([
                'form_id' => $this->form->id,
                'question' => $questionText,
                'type' => 'radio',
                'options' => json_encode($options)
            ]);
        }
    }
}
