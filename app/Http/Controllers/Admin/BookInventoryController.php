<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BookInventoryController extends Controller
{
    public function index()
    {
        // Logic to fetch and display the book inventory
        return view('admin.book-inventory'); // Return the appropriate view
    }
}

