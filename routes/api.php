<?php

use App\Http\Controllers\Api\Bridge\PmsDoctorBridgeController;
use App\Http\Middleware\BridgeTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| PMS bridge (machine-to-machine)
|--------------------------------------------------------------------------
| Auth: Authorization: Bearer {PMS_BRIDGE_TOKEN}
*/
Route::middleware(BridgeTokenMiddleware::class)->prefix('bridge/pms')->group(function () {
    Route::get('doctors', [PmsDoctorBridgeController::class, 'index']);
    Route::get('doctors/{id}', [PmsDoctorBridgeController::class, 'show']);
});
