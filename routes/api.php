<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Master\MasterAdminController;
use App\Http\Controllers\Master\MasterParticipantController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Midtrans\Config;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans/webhook', [PaymentController::class, 'handleNotification'])->name('payment.midtrans-hook');

Route::apiResource('/admins', MasterAdminController::class);
Route::apiResource('/participants', MasterParticipantController::class);

Route::put('/participants/{participant:id}/racepack', [MasterParticipantController::class, 'updateRacepack']);

// Route::put('/midtrans/expire/{orderId}', function ($orderId) {
//     Config::$serverKey = config('services.midtrans.server_key');
//     Config::$isProduction = config('services.midtrans.is_production');
//     Config::$isSanitized = true;
//     Config::$is3ds = true;
//     Config::$overrideNotifUrl = config('services.midtrans.notif_url');

//     $cancel = Midtrans\Transaction::expire($orderId);
//     return response()->json($cancel);
// });
