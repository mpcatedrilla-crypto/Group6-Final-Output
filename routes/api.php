<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - HTTP Basic Authentication
|--------------------------------------------------------------------------
|
| All protected routes require HTTP Basic Authentication header:
| Authorization: Basic base64(username:password)
|
*/

// Auth Test Route (to verify Basic Auth is working)
Route::get('/auth-test', [AuthController::class, 'test']);

// Event Routes - Protected by Basic Auth
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);

// Participant Routes - Protected by Basic Auth
Route::get('/participants', [ParticipantController::class, 'index']);
Route::get('/participants/{id}', [ParticipantController::class, 'show']);
Route::get('/participants/event/{event_id}', [ParticipantController::class, 'byEvent']);
Route::post('/participants', [ParticipantController::class, 'store']);
Route::put('/participants/{id}', [ParticipantController::class, 'update']);
Route::delete('/participants/{id}', [ParticipantController::class, 'destroy']);

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => 'Endpoint not found'], 404);
});
