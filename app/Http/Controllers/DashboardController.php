<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return view('admin.dashboard.index');
        } else if ($user->hasRole('operator')) {
            return view('operator.dashboard.index');
        } else {
            return view('walimurid.dashboard.index');
        }
    }
}
