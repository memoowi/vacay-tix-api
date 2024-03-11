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
            $imageUrls = json_decode($data->image_urls);

            $data->image_urls = $imageUrls;
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
            foreach ($data as $tour) {
                $imageUrls = json_decode($tour->image_urls);
                $tour->image_urls = $imageUrls;
            }
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

        if ($request->hasFile('image_urls')) {
            $image_urls = [];
            foreach ($request->file('image_urls') as $image) {
                $newName = time() . '-' . uniqid() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/tour-images', $newName);
                $image_urls[] = 'storage/tour-images/' . $newName;
            }
        }
        // dd($image_urls);

        try {
            $tour = Tour::create([
                'name' => $request->name,
                'description' => $request->description,
                'image_urls' => json_encode($image_urls),
                'location' => $request->location,
                'price' => $request->price
            ]);

            $tour->image_urls = json_decode($tour->image_urls);

            return response()->json([
                'status' => 'success',
                'data' => $tour
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
                'description' => 'required|string|min:10',
                'image_urls' => 'array',
                'image_urls.*' => 'image',
                'location' => 'required',
                'price' => 'required|numeric'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' =>  $e->errors()
            ], 400);
        }

        if ($request->hasFile('image_urls')) {
            $image_urls = [];
            foreach ($request->file('image_urls') as $image) {
                $newName = time() . '-' . uniqid() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/tour-images', $newName);
                $image_urls[] = 'storage/tour-images/' . $newName;
            }
        } else {
            $image_urls = [];
        }

        try {
            $tour = Tour::find($id);
            $tourCurrentImages = json_decode($tour->image_urls);
            $image_urls = array_merge($tourCurrentImages, $image_urls);

            $tour->update([
                'name' => $request->name,
                'description' => $request->description,
                'image_urls' => json_encode($image_urls),
                'location' => $request->location,
                'price' => $request->price
            ]);
            $tour->image_urls = json_decode($tour->image_urls);

            return response()->json([
                'status' => 'success',
                'data' => $tour
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
