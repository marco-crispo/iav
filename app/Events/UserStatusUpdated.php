<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * UserStatusUpdated
 */
class UserStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * __construct
     *
     * @param  mixed $user
     * @return void
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('users.status'),
        ];
    }

    /**
     * broadcastAs
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'UserStatusUpdated';
    }

    /**
     * broadcastWith
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'status' => $this->user->profile->availability,
        ];
    }
}
