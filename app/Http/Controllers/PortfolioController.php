<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __invoke(): View
    {
        return view('home', config('portfolio'));
    }
}
