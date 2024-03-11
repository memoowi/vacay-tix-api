<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TourController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/tours', [TourController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::patch('/bookings', [BookingController::class, 'cancelBooking']);
    
    Route::middleware('admin')->group(function () {
        Route::post('/tours', [TourController::class, 'store']);
        Route::post('/tours/{id}', [TourController::class, 'update']);
        Route::delete('/tours/{id}', [TourController::class, 'destroy']);
        Route::patch('/tours/{id}', [TourController::class, 'deleteTourImage']);

        Route::get('/admin/bookings', [BookingController::class, 'indexAdminOnTour']);
    });
});