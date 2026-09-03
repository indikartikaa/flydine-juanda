<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{

    public function log(
        $action,
        $tableName = null,
        $recordId = null,
        $description = null
    )
    {

        ActivityLog::create([

            'user_id' => auth()->id(),

            'role' => auth()->user()->role ?? null,

            'action' => $action,

            'table_name' => $tableName,

            'record_id' => $recordId,

            'description' => $description,

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),

        ]);

    }

}
