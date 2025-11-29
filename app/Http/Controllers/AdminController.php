<?php

namespace App\Http\Controllers;

use App\Models\Form;

class AdminController extends Controller
{
    public function index()
    {
        $formCount = Form::count();
        return view('dashboard', compact('formCount'));
    }
}
