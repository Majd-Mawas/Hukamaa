<?php

namespace Modules\PatientManagement\App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\PatientManagement\App\Models\ChatMessage;
use Modules\UserManagement\App\Models\User;

class ChatService
{
    /**
     * Get all chats for a user
     */
    public function getUserChats(int $userId): Collection
    {
        // Get unique users that the current user has chatted with
        $chatPartners = User::whereIn('id', function ($query) use ($userId) {
            $query->select('sender_id')
                ->from('chat_messages')
                ->where('receiver_id', $userId)
                ->union(
                    DB::table('chat_messages')
                        ->select('receiver_id')
                        ->where('sender_id', $userId)
                );
        })->get();

        // For each chat partner, get the latest message
        foreach ($chatPartners as $partner) {
            $latestMessage = ChatMessage::where(function ($query) use ($userId, $partner) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $partner->id);
            })->orWhere(function ($query) use ($userId, $partner) {
                $query->where('sender_id', $partner->id)
                    ->where('receiver_id', $userId);
            })
                ->latest('sent_at')
                ->first();

            $partner->latest_message = $latestMessage;

            // Count unread messages
            $partner->unread_count = ChatMessage::where('sender_id', $partner->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();
        }

        return $chatPartners;
    }

    /**
     * Get messages between two users
     */
    public function getChatMessages(int $userId, int $receiverId): Collection
    {
        return ChatMessage::where(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $receiverId)
                ->where('receiver_id', $userId);
        })
            ->orderBy('sent_at')
            ->get();
    }

    /**
     * Start a new chat (doctor to patient)
     */
    public function startChat(int $AppointmentId, int $doctorId, int $patientId, string $message): ChatMessage
    {
        return $this->sendMessage($AppointmentId, $doctorId, $patientId, $message);
    }

    /**
     * Send a message in an existing chat
     */
    public function sendMessage(int $AppointmentId, int $senderId, int $receiverId, string $message): ChatMessage
    {
        return ChatMessage::create([
            'appointment_id' => $AppointmentId,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markMessagesAsRead(int $senderId, int $receiverId): void
    {
        ChatMessage::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
