<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAllRead(Request $request)
    {
        $user = $request->user();
        
        // Mark all unread notifications as read
        $user->unreadNotifications->each(function($notification) {
            if (empty($notification->read_at)) {
                $notification->markAsRead();
            }
        });
        
        return back();
    }
    
    // Add this if you want to mark single notification as read
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return back();
    }
}