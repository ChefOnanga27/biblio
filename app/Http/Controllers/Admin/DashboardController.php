<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visitor;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::orderBy('email')->get();
        $visitorsCount = Visitor::distinct('ip')->count('ip');
        $totalVisits = Visitor::count();

        return view('admin.dashboard', compact('users', 'visitorsCount', 'totalVisits'));
    }
}
