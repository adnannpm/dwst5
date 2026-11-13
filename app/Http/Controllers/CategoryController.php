<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryData(): JsonResponse {
        $data = categories::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Get all categories successful',
            'data' => $data,
        ]);
    }
}
