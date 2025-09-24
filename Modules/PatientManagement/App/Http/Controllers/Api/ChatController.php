<?php

namespace Modules\PatientManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Enums\UserRole;
use Modules\PatientManagement\App\Http\Requests\SendMessageRequest;
use Modules\PatientManagement\App\Http\Requests\StartChatRequest;
use Modules\PatientManagement\App\Http\Resources\ChatMessageResource;
use Modules\PatientManagement\App\Http\Resources\ChatResource;
use Modules\PatientManagement\App\Services\ChatService;
use Modules\UserManagement\App\Models\User;

class ChatController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ChatService $chatService
    ) {}

    /**
     * Get all chats for the authenticated user
     */
    public function getChat(Appointment $appointment): JsonResponse
    {
        $chats = $appointment->chatMessages;

        return $this->successResponse(
            ChatMessageResource::collection($chats),
            'Chat Messages retrieved successfully'
        );
    }

    /**
     * Start a new chat (only doctors can initiate)
     */
    public function startChat(StartChatRequest $request, Appointment $appointment): JsonResponse
    {
        $user = Auth::user();

        // Only doctors can start a chat
        if ($user->role !== UserRole::DOCTOR->value) {
            return $this->errorResponse('Only doctors can initiate a chat', 403);
        }

        $patientId = $appointment->patient_id;
        $message = $request->validated('message');

        $chatMessage = $this->chatService->sendMessage($appointment->id, $user->id, $patientId, $message);

        return $this->successResponse(
            new ChatMessageResource($chatMessage),
            'Chat started successfully',
            201
        );
    }

    /**
     * Send a message in an existing chat
     */
    public function sendMessage(SendMessageRequest $request, Appointment $appointment): JsonResponse
    {
        $user = Auth::user();

        $receiverId = $user->id === $appointment->doctor_id
            ? $appointment->patient_id
            : $appointment->doctor_id;

        $messageText = $request->validated('message');

        $chatMessage = $this->chatService->sendMessage($appointment->id, $user->id, $receiverId, $messageText);

        $receiver = User::find($receiverId);
        if ($receiver) {
            $receiver->notify(new \App\Notifications\NewChatMessageNotification($chatMessage, $appointment));
        }

        return $this->successResponse(
            new ChatMessageResource($chatMessage),
            'Message sent successfully',
            201
        );
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(User $sender): JsonResponse
    {
        $user = Auth::user();
        $this->chatService->markMessagesAsRead($sender->id, $user->id);

        return $this->successResponse(
            null,
            'Messages marked as read'
        );
    }
}
