<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * UserLogout
 */
class UserLogout implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $user
     */
    public function __construct(public User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('users.logout'),
        ];
    }

    /**
     * broadcastAs
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'UserLogout';
    }

    /**
     * broadcastWith
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->user->location->id,
            'user_id' => $this->user->id
        ];
    }
}
