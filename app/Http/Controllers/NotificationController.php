<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function updateNotificationStatus(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'notification_id' => 'required|exists:tb_notifications,id',
    ]);

    // Update the notification's is_read column to false
    $notification = \App\Models\notifications::findOrFail($request->notification_id);
    $notification->is_read = true;  
    $notification->save();  

    return response()->json(['success' => true]);
}
}
