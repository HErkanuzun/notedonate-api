<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // Sadece show, update ve destroy işlemleri için ownership kontrolü
        $this->middleware('check.ownership')->only(['show', 'update', 'destroy']);
    }

    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        try {
            $query = Event::query()
                ->where('created_by', auth()->id())
                ->with('creator');

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

            $events = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'status' => 'success',
                'data' => EventResource::collection($events),
                'meta' => [
                    'total' => $events->total(),
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch events',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'location' => 'nullable|string|max:255',
                'type' => 'required|string|in:general,meeting,deadline,exam,other',
                'status' => 'required|in:upcoming,ongoing,completed,cancelled'
            ]);

            $validated['created_by'] = auth()->id();
            
            $event = Event::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully',
                'data' => new EventResource($event->load('creator'))
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return new EventResource($event->load('creator'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|date|after_or_equal:today',
                'end_date' => 'sometimes|date|after:start_date',
                'location' => 'nullable|string|max:255',
                'type' => 'sometimes|string|in:general,meeting,deadline,exam,other',
                'status' => 'sometimes|in:upcoming,ongoing,completed,cancelled'
            ]);

            $event->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
                'data' => new EventResource($event->fresh()->load('creator'))
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming events.
     */
    public function upcoming()
    {
        try {
            $events = Event::where('status', 'upcoming')
                ->where('created_by', auth()->id())
                ->where('start_date', '>', Carbon::now())
                ->orderBy('start_date', 'asc')
                ->with('creator')
                ->paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => EventResource::collection($events),
                'meta' => [
                    'total' => $events->total(),
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch upcoming events',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
