<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class TourController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('id')) {
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
        } else {
            $data = Tour::all();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
                'description' => 'required|string',
                'image_url' => 'required',
                'location' => 'required',
                'price' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
}
