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

        $notifications = $request->user()->notifications()
            ->latest()
            ->paginate(15);

        return $this->successResponse(
            [
                'notifications' => $notifications,
            ],
            'Notifications fetched successfully',
            200
        );
    }
}
