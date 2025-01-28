<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard'); // Ensure this Blade view exists
    }
    
    public function userManagement()
    {
        return view('admin.user-management'); // Ensure this Blade view exists
    }
}
