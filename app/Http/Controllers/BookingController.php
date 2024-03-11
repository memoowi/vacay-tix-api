<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

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
}
