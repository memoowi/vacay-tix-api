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

    public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'image_urls' => 'required|array',
            'image_urls.*' => 'required|image',
            'location' => 'required',
            'price' => 'required|numeric'
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 400);
    }

    $tour = new Tour([
        'name' => $request->name,
        'description' => $request->description,
        'location' => $request->location,
        'price' => $request->price,
    ]);

    if ($request->hasFile('image_urls')) {
        $image_urls = [];
        foreach ($request->file('image_urls') as $image) {
            $newName = time() . '-' . uniqid(). '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/tour-images', $newName);
            $image_urls[] = 'storage/tour-images/' . $newName;
        }
        $image_urls = json_encode($image_urls);
        $tour->image_urls = $image_urls;
    }
    // dd($tour->image_urls);

    $tour->save();

    return response()->json([
        'status' => 'success',
        'data' => $tour
    ], 200);
}

}
