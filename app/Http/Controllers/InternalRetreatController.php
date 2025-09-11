<?php

namespace App\Http\Controllers;

use App\Models\InternalRetreat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuditAdminLogController;

class InternalRetreatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'request_for_retreat' => 'required|string|max:255',
            'retreat_no' => 'required|string|max:50|unique:internal_retreats',
            'course_type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|max:255',
            'teachers_name' => 'required|string|max:255',
            'organiser_contact_no' => 'required|string|max:20',
            'notes' => 'nullable|string'
        ]);

        $retreat = InternalRetreat::create($request->all());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'CREATE',
            'entity_area' => 'Internal Retreats',
            'description' => "Created new retreat: {$retreat->retreat_no}",
            'old_values' => json_encode([]),
            'new_values' => json_encode($retreat->toArray()),
        ]);

        return response()->json([
            'message' => 'Retreat created successfully',
            'data' => $retreat
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $retreat = InternalRetreat::findOrFail($id);

        $oldValues = $retreat->toArray();

        $request->validate([
            'request_for_retreat' => 'sometimes|required|string|max:255',
            'retreat_no' => 'sometimes|required|string|max:50|unique:internal_retreats,retreat_no,' . $id,
            'course_type' => 'sometimes|required|string|max:50',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'status' => 'sometimes|required|string|max:255',
            'teachers_name' => 'sometimes|required|string|max:255',
            'organiser_contact_no' => 'sometimes|required|string|max:20',
            'notes' => 'nullable|string'
        ]);

        $retreat->update($request->all());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'UPDATE',
            'entity_area' => 'Internal Retreats',
            'description' => "Updated retreat: {$retreat->retreat_no}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($retreat->fresh()->toArray()),
        ]);

        return response()->json([
            'message' => 'Retreat updated successfully',
            'data' => $retreat
        ]);
    }

    public function destroy($id)
    {
        $retreat = InternalRetreat::findOrFail($id);
        $oldValues = $retreat->toArray();

        $retreat->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'DELETE',
            'entity_area' => 'Internal Retreats',
            'description' => "Deleted retreat: {$retreat->retreat_no}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode([]),
        ]);

        return response()->json([
            'message' => 'Retreat deleted successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:internal_retreats,id'
        ]);

        $deletedCount = 0;
        $retreatNumbers = [];

        foreach ($request->ids as $id) {
            $retreat = InternalRetreat::find($id);
            if ($retreat) {
                $oldValues = $retreat->toArray();
                $retreatNumbers[] = $retreat->retreat_no;

                $retreat->delete();
                $deletedCount++;

                // Create admin audit log for each deletion
                AuditAdminLogController::createLog([
                    'user_id' => Auth::id(),
                    'action_type' => 'DELETE',
                    'entity_area' => 'Internal Retreats',
                    'description' => "Deleted retreat: {$retreat->retreat_no}",
                    'old_values' => json_encode($oldValues),
                    'new_values' => json_encode([]),
                ]);
            }
        }

        return response()->json([
            'message' => "{$deletedCount} retreat(s) deleted successfully",
            'deleted_retreats' => $retreatNumbers
        ]);
    }

    public function index()
    {
        // Get all retreats, latest first
        $retreats = InternalRetreat::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $retreats
        ]);
    }


    public function show($id)
    {
        $retreat = InternalRetreat::findOrFail($id);

        return response()->json([
            'data' => $retreat
        ]);
    }
}
