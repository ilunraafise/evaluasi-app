<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'question',
        'type',
        'options', // untuk checkbox/ multiple choice
    ];

    // Otomatis cast kolom options ke array
    protected $casts = [
        'options' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }

}
