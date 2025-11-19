<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Events\NewMessageSent;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * MessageController
 */
class MessageController extends BaseController
{
    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $messages =  Message::with('sender', 'receiver')->paginate(20);
        return $this->successResponse($messages->items(), 'Messages retrieved successfully', Response::HTTP_OK);
    }



   /**
    * myMessages
    *
    * @return JsonResponse
    */
   public function myMessages(): JsonResponse
    {
        $userId = Auth::id();

        // Retrieve messages where the user is sender or receiver
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Group by correspondent (other participant)
        $grouped = $messages->groupBy(function ($msg) use ($userId) {
            return $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
        });

        // Clean output structure
        $groupedMessages = $grouped->map(function ($msgs, $otherUserId) use ($userId) {
            return [
                'user' => $msgs->first()->sender_id === $userId
                    ? $msgs->first()->receiver->profile
                    : $msgs->first()->sender->profile,
                'messages' => $msgs->map(function ($m) use ($userId) {
                    return [
                        'id' => $m->id,
                        'content' => $m->content,
                        'fromMe' => $m->sender_id === $userId,
                        'timestamp' => $m->created_at->toDateTimeString(),
                    ];
                }),
            ];
        })->values();

        return $this->successResponse($groupedMessages, 'User messages retrieved successfully', Response::HTTP_OK);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request): JsonResponse
    {
        $data = $request->validated();

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $data['receiver_id'],
            'content' => $data['content'],
        ]);

        broadcast(new NewMessageSent($message))->toOthers();


        return $this->successResponse($message, 'Message created successfully', Response::HTTP_CREATED);
    }

    /**
     * show
     *
     * @param  mixed $message
     * @return JsonResponse
     */
    public function show(Message $message): JsonResponse
    {
        return $this->successResponse($message, 'Message retrieved successfully', Response::HTTP_OK);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $message
     * @return JsonResponse
     */
    public function update(UpdateMessageRequest $request, Message $message): JsonResponse
    {
        $message->update($request->validated());
        return $this->successResponse($message, 'Message updated successfully', Response::HTTP_OK);
    }

    /**
     * destroy
     *
     * @param  mixed $message
     * @return JsonResponse
     */
    public function destroy(Message $message): JsonResponse
    {
        $message->delete();
        return $this->successResponse(null, 'Message deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
