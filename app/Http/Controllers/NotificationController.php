<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
	public function index()
	{
		return response()->json(['notifications' => Notification::where('receiver_id', auth()->user()->id)->with('trigerer')->orderBy('created_at', 'desc')->get()]);
	}

	public function update(Notification $notification)
	{
		$notification->update(['is_read' => true]);
		return response()->json(['notification' => $notification]);
	}

	public function readAll()
	{
		Notification::where('receiver_id', auth()->user()->id)->update(['is_read' => true]);
		return response()->json(['message' => 'all notifications marked as read']);
	}
}
