<?php

namespace App\Http\Controllers;

use App\Models\AuditUserLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditUserLogController extends Controller
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
        AuditUserLog::create($data);
    }
}
