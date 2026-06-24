<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;

class HomeController extends Controller
{
    public function index()
    {
        $content = HomepageContent::first();

        return view('public.home', compact('content'));
    }
}