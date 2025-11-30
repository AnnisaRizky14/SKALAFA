<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Base query
        $query = Notification::latest();

        // Filter notifications based on user role
        if ($user->isFacultyAdmin()) {
            // Faculty admin: only show notifications related to their faculty
            $accessibleFacultyIds = $user->getAccessibleFacultyIds();
            $query->forFaculties($accessibleFacultyIds);
        }
        // Super admin: no filtering, sees all notifications

        $notifications = $query->paginate(20);

        // Unread count with same filtering
        $unreadQuery = Notification::unread();
        if ($user->isFacultyAdmin()) {
            $unreadQuery->forFaculties($user->getAccessibleFacultyIds());
        }
        $unreadCount = $unreadQuery->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Notification::unread()->count()
        ]);
    }
}
