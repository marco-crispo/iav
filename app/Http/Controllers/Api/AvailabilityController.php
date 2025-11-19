<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreAvailabilityRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Models\Availability;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * AvailabilityController
 */
class AvailabilityController extends BaseController
{
    /**
     * index
     *
     * @return void
     */
    public function index() : JsonResponse
    {
        return $this->successResponse(Availability::all(), 'Availabilities retrieved successfully', Response::HTTP_OK);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreAvailabilityRequest $request)
    {
        //
    }

    /**
     * show
     *
     * @param  mixed $availability
     * @return void
     */
    public function show(Availability $availability)
    {
        //
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $availability
     * @return void
     */
    public function update(UpdateAvailabilityRequest $request, Availability $availability)
    {
        //
    }

    /**
     * destroy
     *
     * @param  mixed $availability
     * @return void
     */
    public function destroy(Availability $availability)
    {
        //
    }
}
