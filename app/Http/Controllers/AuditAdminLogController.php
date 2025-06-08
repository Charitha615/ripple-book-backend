<?php

namespace App\Http\Controllers;

use App\Models\AuditAdminLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditAdminLogController extends Controller
{
    public static function createLog(array $data)
    {
        if (!empty($data['user_id'])) {
            $user = User::find($data['user_id']);

            // If user is found, populate the user-related fields
            if ($user) {
                $data['user_name'] = $user->name;
                $data['user_role'] = $user->user_type ?? 'User';
            } else {
                // If user not found, set default values
                $data['user_name'] = 'Unknown User';
                $data['user_role'] = 'Unknown Role';
            }
        } else {
            // If 'user_id' is not provided, set default values
            $data['user_name'] = 'Guest';
            $data['user_role'] = 'Guest';
        }

        // Automatically get IP address and current timestamp for action_date_time
        $data['ip_address'] = request()->ip();
        $data['action_date_time'] = now();

        // Save the log entry to the database
        AuditAdminLog::create($data);
    }

    /**
     * Get all audit logs with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15); // Default to 15 items per page
        $logs = AuditAdminLog::orderBy('action_date_time', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }
}
