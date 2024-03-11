<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
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

    public function cancelBooking(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:bookings,id'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
        $booking = Booking::find($request->booking_id);
        $booking->status = 'canceled';
        $booking->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Booking canceled'
        ], 200);
    }

    public function show(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:bookings,id'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
        $booking = Booking::find($request->booking_id);
        $booking->load(['tour', 'user', 'payment']);
        return response()->json([
            'status' => 'success',
            'data' => $booking
        ], 200);
    }
}
