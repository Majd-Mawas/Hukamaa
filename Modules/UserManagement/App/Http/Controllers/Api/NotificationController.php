<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationTemplateBuilder;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(
        public NotificationTemplateBuilder $notification_template_builder

    ) {}

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
        if (request()->header('time-zone')) {
            Auth::user()->update([
                'timezone' => request()->header('time-zone')
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
