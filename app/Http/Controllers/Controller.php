<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function ensurePermission(Request $request, string $permission): void
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin() && $user->hasPermission($permission), 403, 'Permission insuffisante.');
    }

    protected function logAdminActivity(Request $request, string $action, ?string $targetType = null, mixed $targetId = null, ?string $details = null): void
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            return;
        }

        $user->forceFill([
            'last_seen_at' => now(),
            'last_operation' => $action,
        ])->save();

        AdminActivityLog::create([
            'admin_id' => $user->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'details' => $details,
            'ip_address' => $request->ip(),
        ]);
    }
}
