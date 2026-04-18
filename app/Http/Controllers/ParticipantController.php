<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AuthController;

class ParticipantController extends Controller
{
    /**
     * Display a listing of all participants
     */
    public function index(Request $request)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $participants = Participant::with('event')
                                   ->orderBy('id', 'desc')
                                   ->get();
        
        return response()->json($participants);
    }

    /**
     * Store a newly created participant
     */
    public function store(Request $request)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
            'event_id' => 'required|exists:events,id'
        ]);

        // Verify the event exists
        $event = Event::find($validated['event_id']);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        try {
            $participant = Participant::create($validated);

            return response()->json([
                'message' => 'Participant registered successfully',
                'participant' => $participant
            ], 201);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'unique constraint')) {
                return response()->json(['message' => 'Email already registered'], 409);
            }
            throw $e;
        }
    }

    /**
     * Display a single participant
     */
    public function show(Request $request, string $id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $participant = Participant::with('event')->find($id);

        if (!$participant) {
            return response()->json(['message' => 'Participant not found'], 404);
        }

        return response()->json($participant);
    }

    /**
     * Get participants by event
     */
    public function byEvent(Request $request, string $event_id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $participants = Participant::where('event_id', $event_id)
                                   ->orderBy('id', 'desc')
                                   ->get();

        return response()->json([
            'event' => $event->title,
            'participants' => $participants
        ]);
    }

    /**
     * Update the specified participant
     */
    public function update(Request $request, string $id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $participant = Participant::find($id);

        if (!$participant) {
            return response()->json(['message' => 'Participant not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:participants,email,' . $id,
            'event_id' => 'sometimes|exists:events,id'
        ]);

        try {
            $participant->update($validated);

            return response()->json([
                'message' => 'Participant updated successfully',
                'participant' => $participant
            ]);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'unique constraint')) {
                return response()->json(['message' => 'Email already in use'], 409);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified participant
     */
    public function destroy(Request $request, string $id)
    {
        // Validate Basic Authentication
        $user = AuthController::validateBasicAuth($request);
        if (!$user) {
            return AuthController::unauthorizedResponse();
        }
        
        $participant = Participant::find($id);

        if (!$participant) {
            return response()->json(['message' => 'Participant not found'], 404);
        }

        $participant->delete();

        return response()->json([
            'message' => 'Participant deleted successfully'
        ]);
    }
}
