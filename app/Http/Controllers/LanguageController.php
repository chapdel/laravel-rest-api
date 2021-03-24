<?php

namespace App\Http\Controllers;

use App\Http\Resources\Language\LanguageResourceCollection;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(new LanguageResourceCollection(Language::all()));
    }
}
