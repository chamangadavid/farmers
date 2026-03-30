<?php

namespace App;

use App\Models\Audit\AuditLog;

trait Auditable
{
    public function audit($action, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
