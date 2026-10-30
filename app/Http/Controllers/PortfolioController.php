<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function home(): View
    {
        return view('home', config('portfolio'));
    }

    public function privacy(): View
    {
        return view('privacy', config('portfolio'));
    }
}
