<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Throwable;

class TourController extends Controller
{
    public function index()
    {
        $data = Tour::all();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
    public function show(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 400);
        }
        $data = Tour::find($request->id);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tour not found'
            ], 404);
        }
    }
}
