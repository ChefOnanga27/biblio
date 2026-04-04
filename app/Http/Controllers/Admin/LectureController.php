<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecture;

class LectureController extends Controller
{
    public function index()
    {
        $lectures = Lecture::with(['user', 'livre'])->latest('read_at')->paginate(20);

        return view('admin.lectures.index', compact('lectures'));
    }
}
