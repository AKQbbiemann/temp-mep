<?php

namespace App\Http\Controllers;

use App;

class LocalizationController extends Controller
{
    public function switchLang($locale)
    {
        App::setlocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
