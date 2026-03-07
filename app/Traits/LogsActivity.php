<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait and register model events.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logAction('CREATED', $model);
        });

        static::updated(function ($model) {
            self::logAction('UPDATED', $model);
        });

        static::deleted(function ($model) {
            self::logAction('DELETED', $model);
        });
    }

    /**
     * Helper to log custom actions.
     */
    public static function logCustomAction(string $action, string $description)
    {
        // Don't log if running in CLI (like tests or seeders) unless there is an auth user.
        if (!Auth::check() && app()->runningInConsole()) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => null,
            'model_id' => null,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Generic log insertion.
     */
    protected static function logAction($action, $model)
    {
        // Don't log if running in CLI (like tests or seeders) without an authenticated user.
        if (!Auth::check() && app()->runningInConsole()) {
            return;
        }

        $userId = Auth::id();
        $modelType = get_class($model);
        $modelId = $model->id;
        
        $modelName = class_basename($modelType);
        $description = "{$action} {$modelName} #{$modelId}";

        // Detailed description for updates
        if ($action === 'UPDATED') {
            $changes = $model->getDirty();
            unset($changes['updated_at']);
            $changedFields = implode(', ', array_keys($changes));
            $description .= " (Diubah: {$changedFields})";
        }

        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
