<?php

namespace App\Http\Controllers;

use App\Models\DhammaTalk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DhammaTalkController extends Controller
{
    /**
     * Display a listing of the resource with optional filtering
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = DhammaTalk::query();

            // Search by any field
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Filter by sutta title
            if ($request->has('sutta_title')) {
                $query->where('sutta_title', 'like', "%{$request->sutta_title}%");
            }

            // Filter by event title
            if ($request->has('event_title')) {
                $query->where('event_title', 'like', "%{$request->event_title}%");
            }

            // Filter by retreat number
            if ($request->has('retreat_no')) {
                $query->byRetreatNo($request->retreat_no);
            }

            // Filter by location
            if ($request->has('location')) {
                $query->byLocation($request->location);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            // Sort results
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $talks = $query->get();

            return response()->json([
                'success' => true,
                'data' => $talks,
                'filters' => $request->all()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching dhamma talks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dhamma talks'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sutta_title' => 'required|string|max:255',
            'event_title' => 'required|string|max:255',
            'retreat_no' => 'nullable|string|max:100',
            'event_location' => 'nullable|string|max:255',
            'event_start_date' => 'nullable|date',
            'event_end_date' => 'nullable|date|after_or_equal:event_start_date',
            'links' => 'nullable|array',
            'links.*.title' => 'required_with:links|string|max:255',
            'links.*.url' => 'required_with:links|url',
            'links.*.type' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $talk = DhammaTalk::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Dhamma talk created successfully',
                'data' => $talk
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating dhamma talk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create dhamma talk'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $talk = DhammaTalk::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $talk
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dhamma talk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Dhamma talk not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $talk = DhammaTalk::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'sutta_title' => 'sometimes|required|string|max:255',
                'event_title' => 'sometimes|required|string|max:255',
                'retreat_no' => 'nullable|string|max:100',
                'event_location' => 'nullable|string|max:255',
                'event_start_date' => 'nullable|date',
                'event_end_date' => 'nullable|date|after_or_equal:event_start_date',
                'links' => 'nullable|array',
                'links.*.title' => 'required_with:links|string|max:255',
                'links.*.url' => 'required_with:links|url',
                'links.*.type' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $talk->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Dhamma talk updated successfully',
                'data' => $talk
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating dhamma talk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update dhamma talk'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $talk = DhammaTalk::findOrFail($id);
            $talk->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dhamma talk deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting dhamma talk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete dhamma talk'
            ], 500);
        }
    }

    /**
     * Search dhamma talks with advanced filtering
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = DhammaTalk::query();

            // Multiple field search
            if ($request->has('q')) {
                $query->search($request->q);
            }

            // Individual field filters
            $filters = $request->only([
                'sutta_title', 'event_title', 'retreat_no', 'event_location'
            ]);

            foreach ($filters as $field => $value) {
                if ($value) {
                    $query->where($field, 'like', "%{$value}%");
                }
            }

            // Date range filter
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->where(function ($q) use ($request) {
                    $q->whereBetween('event_start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('event_end_date', [$request->start_date, $request->end_date]);
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $talks = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $talks->items(),
                'pagination' => [
                    'current_page' => $talks->currentPage(),
                    'per_page' => $talks->perPage(),
                    'total' => $talks->total(),
                    'last_page' => $talks->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching dhamma talks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search dhamma talks'
            ], 500);
        }
    }
}
