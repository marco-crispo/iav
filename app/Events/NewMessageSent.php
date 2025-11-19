<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

/**
 * NewMessageSent
 */
class NewMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * __construct
     *
     * @param  mixed $message
     * @return void
     */
    public function __construct(public Message $message)
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('messages.sent.' . $this->message->receiver_id),
        ];
    }

    /**
     * broadcastAs
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'NewMessageSent';
    }

    /**
     * broadcastWith
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->profile->name,
            'receiver_id' => $this->message->receiver_id,
            'receiver_name' => $this->message->receiver->profile->name,
            'content' => $this->message->content,
            'is_read' => $this->message->is_read,
        ];
    }
}
