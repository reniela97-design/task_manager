<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Kuhaon ang tanan notifications sa user
        $notifications = auth()->user()->notifications()->paginate(15);
        
        // I-mark as read sila tanan inig abli sa page (optional)
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}