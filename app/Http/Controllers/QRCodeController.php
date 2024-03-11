<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\QRCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QRCodeController extends Controller
{
    public function generateQRCode(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:bookings,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }

        $booking = Booking::find($request->booking_id);

        if ($booking->status != 'confirmed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not confirmed'
            ], 400);
        }

        $timestamp = now()->timestamp;
        $uniqueString = $booking->id . '_' . $timestamp;
        $qrCode = hash('sha256', $uniqueString);
        // dd($qrCode);

        $booking->qrCode()->updateOrCreate(
            ['booking_id' => $booking->id],
            ['qr_code' => $qrCode]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'QR Code generated successfully'
        ], 200);
    }

    public function getQRCode(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:bookings,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }

        $booking = Booking::find($request->booking_id);
        $qrCode = $booking->qrCode;

        if (!$qrCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $qrCode
        ], 200);
    }

    public function useQRCode(Request $request)
    {
        
    }
}
