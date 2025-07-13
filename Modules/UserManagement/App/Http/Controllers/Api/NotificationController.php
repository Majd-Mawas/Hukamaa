<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(15);

        if (request()->header('fcm-token')) {
            Auth::user()->update([
                'fcm_token' => request()->header('fcm-token')
            ]);
        }

        return $this->successResponse(
            [
                'notifications' => $notifications,
            ],
            'Notifications fetched successfully',
            200
        );
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return $this->successResponse(
            [],
            'Notifications marked as read successfully',
            200
        );
    }
}
