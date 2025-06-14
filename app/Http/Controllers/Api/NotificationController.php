<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\Api\NotificationTransformer;

class NotificationController extends Controller
{
    /**
     * Get all Notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $notifications = request()->user()->notifications;
        return response()->json(fractal($notifications, new NotificationTransformer())->toArray()['data'], 200);
    }

    /**
     * Mark a notification as read
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        request()->user()
            ->unreadNotifications
            ->when($id, function ($query) use ($id) {
                return $query->where('id',$id);
            })
            ->markAsRead();

        return response()->json(['success' => true, "message" => "Notification marked as read."],200);
    }

    /**
     * Mark all notification as read
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        request()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true, "message" => "All Notification marked as read."],200);
    }

    /**
     * Delete a notification
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification($id)
    {
        request()->user()
            ->notifications()
            ->when($id, function ($query) use ($id) {
                return $query->where('id',$id);
            })
            ->delete();

        return response()->json(['success' => true, "message" => "Notification deleted."],200);
    }

    /**
     * Delete all notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllNotifications()
    {
        request()->user()->notifications()->delete();

        return response()->json(['success' => true, "message" => "All notification are deleted."],200);
    }
}
