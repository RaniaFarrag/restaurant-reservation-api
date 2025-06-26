<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meal;

class MealController extends Controller
{

    public function listMenuItems()
    {
        $meals = Meal::where('available_quantity', '>', 0)->get();

        return response()->json([
            'meals' => $meals
        ]);
    }
}
