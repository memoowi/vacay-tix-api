<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function payBooking(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|integer|exists:bookings,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 400);
        }

        $booking = Booking::find($request->booking_id);

        if ($booking->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking already paid',
            ], 400);
        }
        $booking->payment()->create([
            'payment_date' => now(),
            'amount' => $booking->total_price,
        ]);

        $booking->update([
            'status' => 'confirmed',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful',
        ], 200);
    }
}
