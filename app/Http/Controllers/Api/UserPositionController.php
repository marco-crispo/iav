<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserPositionRequest;
use App\Http\Requests\UpdateUserPositionRequest;
use App\Models\UserPosition;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Events\UserLocationUpdated;
use App\Http\Requests\UserFilterRequest;

/**
 * UserPositionController
 */
class UserPositionController extends BaseController
{
    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $userPosition =  UserPosition::with('user')->paginate(20);
        return $this->successResponse($userPosition->items(), 'User positions retrieved successfully', Response::HTTP_OK);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(StoreUserPositionRequest $request): JsonResponse
    {
        $userPosition = UserPosition::create([
            'user_id' => $request->user()->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return $this->successResponse($userPosition, 'User position created successfully', Response::HTTP_CREATED);
    }

    /**
     * show
     *
     * @param  mixed $userPosition
     * @return JsonResponse
     */
    public function show(UserPosition $userPosition): JsonResponse
    {
        return $this->successResponse($userPosition::with('user'), 'User position retrieved successfully', Response::HTTP_OK);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $userPosition
     * @return JsonResponse
     */
    public function update(UpdateUserPositionRequest $request, UserPosition $userPosition): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $userPosition->update([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);


        if ($userPosition->wasChanged()) {
            if ($userPosition->wasChanged(['latitude', 'longitude'])) {
                broadcast(new UserLocationUpdated($user));
            }
        }
        return $this->successResponse($userPosition, 'User position updated successfully', Response::HTTP_OK);
    }

    /**
     * destroy
     *
     * @param  mixed $userPosition
     * @return JsonResponse
     */
    public function destroy(UserPosition $userPosition): JsonResponse
    {
        $userPosition->delete();
        return $this->successResponse(null, 'User position deleted successfully', Response::HTTP_NO_CONTENT);
    }

    /**
     * nearby
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function nearby(UserFilterRequest $request): JsonResponse
    {
        $filters = $request->toUserFilter();
        $userPositions = UserPosition::nearby($filters->latitude, $filters->longitude, $filters->filters)->with('user')->get();
        return $this->successResponse($userPositions, 'Nearby user positions retrieved successfully', Response::HTTP_OK);
    }
}
