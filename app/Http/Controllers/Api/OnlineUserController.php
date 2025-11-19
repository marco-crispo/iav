<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\OnlineUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * OnlineUserController
 */
class OnlineUserController extends BaseController
{
    /**
     * enter
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function enter(Request $request): JsonResponse
    {
        OnlineUser::updateOrCreate(
            ['user_id' => $request->user_id],
            ['status' => 'online', 'last_seen_at' => now()]
        );

        return $this->successResponse([], 'User entered online status successfully', Response::HTTP_OK);
    }

    /**
     * leave
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function leave(Request $request): JsonResponse
    {
        OnlineUser::where('user_id', $request->user_id)->delete();
        return $this->successResponse([], 'User left online status successfully', Response::HTTP_OK);
    }
}
