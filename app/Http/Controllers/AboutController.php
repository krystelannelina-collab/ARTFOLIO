<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    /**
     * Show the About page: a short introduction, contact details, and the feedback form.
     */
    public function __invoke(): View
    {
        return view('about');
    }
}
