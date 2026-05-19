<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\NotificationApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = NotificationApp::where('user_id', Auth::id())
            ->latest()
            ->limit(5)
            ->get(['id', 'titre', 'message', 'type', 'lue', 'created_at']);

        return response()->json($notifications);
    }

    public function markRead(NotificationApp $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['lue' => true, 'date_lecture' => now()]);

        return response()->json(['success' => true]);
    }
}
