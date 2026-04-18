<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class EventController extends Controller
{
    /**
     * Display a listing of all events
     */
    public function index(Request $request)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        try {
            $events = Event::orderBy('event_date', 'desc')->get();
            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date'
        ]);

        $event = Event::create($validated);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }

    /**
     * Display a single event
     */
    public function show(Request $request, string $id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $event = Event::with('participants')->find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event);
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, string $id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'sometimes|date'
        ]);

        $event->update($validated);

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    /**
     * Remove the specified event
     */
    public function destroy(Request $request, string $id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }
}
