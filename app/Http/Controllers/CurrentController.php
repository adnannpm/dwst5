<?php

namespace App\Http\Controllers;

use App\Models\currencies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrentController extends Controller
{
    public function currentData(): JsonResponse {
        $datas = currencies::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Get all currencies successful',
            'data' => $datas
        ]);
    }
}
