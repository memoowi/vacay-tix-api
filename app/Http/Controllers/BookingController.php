<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Throwable;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()->bookings;
        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ], 200);
    }

    public function indexAdminOnTour(Request $request)
    {
        $tour = $request->tour_id;
        $bookings = Booking::where('tour_id', $tour)->get();
        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tour_id' => 'required|exists:tours,id',
                'booking_date' => 'required|date',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 400);
        }
        $booking = $request->user()->bookings()->create([
            'tour_id' => $request->tour_id,
            'booking_date' => $request->booking_date,
            'total_price' => Tour::find($request->tour_id)->price
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $booking
        ], 201);
    }
}
