<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserActivityLog extends Model
{
    use HasFactory;

    protected $table = 'user_activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Static helper to log an activity.
     */
    public static function log(string $action, string $description, ?array $properties = null, ?int $userId = null, ?string $subjectType = null, ?int $subjectId = null)
    {
        try {
            $request = request();
            $ip = $request ? $request->ip() : null;
            $userAgent = $request ? $request->header('User-Agent') : null;

            return self::create([
                'user_id' => $userId ?? Auth::id(),
                'action' => strtoupper($action),
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'description' => $description,
                'properties' => $properties,
                'ip_address' => $ip,
                'user_agent' => $userAgent ? substr($userAgent, 0, 1024) : null,
            ]);
        } catch (\Exception $e) {
            // Silently log error to laravel logs so analytics logging doesn't block core operations
            logger()->error('UserActivityLog error: ' . $e->getMessage());
        }
    }
}
