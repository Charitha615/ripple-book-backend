<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    /**
     * Create a user log entry.
     *
     * @param array $data
     * @return void
     */
    public static function createLog(array $data)
    {
        // If user_id is provided, fetch user details
        if (!empty($data['user_id'])) {
            $user = User::find($data['user_id']);

            // If user is found, populate user-related fields
            if ($user) {
                $data['user_name'] = $user->name;
                $data['user_role'] = $user->user_type ?? 'User';
            } else {
                // If user not found, set default values
                $data['user_name'] = 'Unknown User';
                $data['user_role'] = 'Unknown Role';
            }
        } else {
            // If user_id is not provided, set default values for guests
            $data['user_name'] = 'Guest';
            $data['user_role'] = 'Guest';
        }

        // Automatically get IP address and current timestamp
        $data['ip_address'] = request()->ip();
        $data['action_date_time'] = now();

        // Save the log entry to the database
        UserLog::create($data);
    }

    /**
     * Get all user logs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllLogs(Request $request)
    {
//        $logs = UserLog::orderBy('action_date_time', 'desc')->get();
//        return response()->json($logs);

        $perPage = $request->input('per_page', 15); // Default to 15 items per page
        $logs = UserLog::orderBy('action_date_time', 'desc')
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

    /**
     * Get logs for a specific user
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLogs($userId)
    {
        $logs = UserLog::where('user_id', $userId)
            ->orderBy('action_date_time', 'desc')
            ->get();
        return response()->json($logs);
    }
}
