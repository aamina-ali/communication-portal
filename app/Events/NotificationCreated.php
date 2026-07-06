<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Notification $notification)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->notification->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NotificationCreated';
    }

    public function broadcastWith(): array
    {
        // Load the sender if it hasn't been loaded
        if (!$this->notification->relationLoaded('sender')) {
            $this->notification->load('sender');
        }

        return [
            'notification_id' => $this->notification->id ?? $this->notification->getKey(),
            'user_id'         => $this->notification->user_id,
            'sender_id'       => $this->notification->sender_id,
            'type'            => $this->notification->type,
            'workspace_id'    => $this->notification->workspace_id,
            'channel_id'      => $this->notification->channel_id,
            'message_id'      => $this->notification->message_id,
            'text'            => $this->notification->text,
            'is_seen'         => (bool)$this->notification->is_seen,
            'created_at'      => $this->notification->created_at?->diffForHumans() ?? 'Just now',
            'sender'          => [
                'username'   => $this->notification->sender?->username ?? 'System',
                'avatar_url' => $this->notification->sender?->avatar_url,
            ],
        ];
    }
}
