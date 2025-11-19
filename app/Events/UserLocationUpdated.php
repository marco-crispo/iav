<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

/**
 * UserLocationUpdated
 */
class UserLocationUpdated implements ShouldBroadcast
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
            new Channel('users.location'),
        ];
    }

    /**
     * broadcastAs
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'UserLocationUpdated';
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
            'user_id' => $this->user->id,
            'latitude' => $this->user->location->latitude,
            'longitude' => $this->user->location->longitude,
            'status' => optional($this->user->profile->availability)->status, // Handles null availability relationship
            'user' => $this->user
        ];
    }
}
