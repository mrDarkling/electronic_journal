<?php

namespace App\Http\Controllers;

use App\Model\StudySubject;
use Illuminate\Http\Request;

class StudySubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subjects');
    }
}
