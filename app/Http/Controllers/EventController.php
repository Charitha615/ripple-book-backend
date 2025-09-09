<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        try {
            $events = Event::with('admin')->get();

            return response()->json([
                'success' => true,
                'data' => $events,
                'message' => 'Events retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location_details' => 'nullable|string',
            'organizer' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string', // Base64 encoded images
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'url' => 'nullable|url',
            'other_details' => 'nullable|string',
            'admin_id' => 'required|exists:users,id',
            'is_visible' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $event = new Event();
            $event->fill($request->except('images'));

            // Set default value if not provided (optional)
            if (!$request->has('is_visible')) {
                $event->is_visible = true; // Default to visible
            }

            // Process and store images if provided
            if ($request->has('images') && !empty($request->images)) {
                $event->images = $event->processAndStoreImages($request->images);
            }

            $event->save();

            // Log to AuditAdminLog
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Create',
                'entity_area' => 'Events',
                'description' => "Created new event: {$event->title} (ID: {$event->id})",
                'old_values' => json_encode(null),
                'new_values' => json_encode($event->toArray()),
            ]);

            return response()->json([
                'success' => true,
                'data' => $event->load('admin'),
                'message' => 'Event created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        try {
            $event = Event::with('admin')->find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $event,
                'message' => 'Event retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location_details' => 'nullable|string',
            'organizer' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string', // Base64 encoded images
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'url' => 'nullable|url',
            'other_details' => 'nullable|string',
            'admin_id' => 'sometimes|exists:users,id',
            'is_visible' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.'
                ], 404);
            }

            $oldValues = $event->toArray();

            $event->fill($request->except('images'));

            // Process and store new images if provided
            if ($request->has('images') && !empty($request->images)) {
                // Delete old images
                if ($event->images) {
                    foreach ($event->images as $imagePath) {
                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }
                }

                $event->images = $event->processAndStoreImages($request->images);
            }

            $event->save();

            // Log to AuditAdminLog
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Events',
                'description' => "Updated event: {$event->title} (ID: {$event->id})",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($event->toArray()),
            ]);

            return response()->json([
                'success' => true,
                'data' => $event->load('admin'),
                'message' => 'Event updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete the specified event.
     */
    public function destroy($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.'
                ], 404);
            }

            $oldValues = $event->toArray();

            $event->delete();

            // Log to AuditAdminLog
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Events',
                'description' => "Deleted event: {$event->title} (ID: {$event->id})",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(null),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft deleted event.
     */
    public function restore($id)
    {
        try {
            $event = Event::withTrashed()->find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.'
                ], 404);
            }

            if (!$event->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event is not deleted.'
                ], 400);
            }

            $oldValues = $event->toArray();

            $event->restore();

            // Log to AuditAdminLog
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Restore',
                'entity_area' => 'Events',
                'description' => "Restored event: {$event->title} (ID: {$event->id})",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($event->toArray()),
            ]);

            return response()->json([
                'success' => true,
                'data' => $event->load('admin'),
                'message' => 'Event restored successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all events including soft deleted ones.
     */
    public function indexWithTrashed()
    {
        try {
            $events = Event::with('admin')->withTrashed()->get();

            return response()->json([
                'success' => true,
                'data' => $events,
                'message' => 'Events (including trashed) retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
