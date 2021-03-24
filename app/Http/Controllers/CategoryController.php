<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryResourceCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->type) {
            switch ($request->type) {
                case 'game':
                    return response()->json(new CategoryResourceCollection(Category::games()->get()));
                    break;

                default:
                    return response()->json(new CategoryResourceCollection(Category::apps()->get()));
                    break;
            }
        }
        return response()->json(new CategoryResourceCollection(Category::all()));
    }
}
