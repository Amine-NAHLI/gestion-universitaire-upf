<?php

namespace App\Http\Controllers;

use App\Models\NotificationApp;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->where('lue', false)
            ->latest()
            ->get();
            
        return response()->json($notifications);
    }

    public function markAsRead(NotificationApp $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update([
            'lue' => true,
            'date_lecture' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->where('lue', false)
            ->update([
                'lue' => true,
                'date_lecture' => now()
            ]);

        return response()->json(['success' => true]);
    }
}
