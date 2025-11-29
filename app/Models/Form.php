<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    // Field yang boleh diinput ke database
    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    public function admin() {
        return $this->belongsTo(Admin::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
