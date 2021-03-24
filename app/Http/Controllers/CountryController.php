<?php

namespace App\Http\Controllers;

use App\Http\Resources\Country\CountryResourceCollection;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(new CountryResourceCollection(Country::all()));
    }
}
