<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::query()->with(['creator', 'comments']);

        // Filtreleme
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Sıralama
        $query->orderBy('start_date', 'asc');

        $events = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string',
            'type' => 'required|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled'
        ]);

        $event = Event::create([
            ...$validated,
            'created_by' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Event created successfully',
            'data' => $event->load(['creator', 'comments'])
        ], 201);
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return response()->json([
            'status' => 'success',
            'data' => $event->load(['creator', 'comments'])
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'location' => 'nullable|string',
            'type' => 'sometimes|string',
            'status' => 'sometimes|in:upcoming,ongoing,completed,cancelled'
        ]);

        $event->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Event updated successfully',
            'data' => $event->load(['creator', 'comments'])
        ]);
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * Get upcoming events.
     */
    public function upcoming()
    {
        $events = Event::where('status', 'upcoming')
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->with(['creator', 'comments'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);
    }

    /**
     * Display a listing of public events.
     */
    public function publicIndex()
    {
        $events = Event::where('status', 'public')->get();
        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);
    }
}
