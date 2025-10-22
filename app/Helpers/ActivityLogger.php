<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\EmployeeLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function admin($action, $model = null, $changes = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }

    public static function employee($action, $meta = null)
    {
        if (!Auth::check())
            return;

        EmployeeLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'meta' => $meta,
        ]);
    }
}
