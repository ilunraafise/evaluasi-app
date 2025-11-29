<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'origin_school',
        'level',
        'region',
        'batch',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
