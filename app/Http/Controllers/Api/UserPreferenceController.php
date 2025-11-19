<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserPreferenceRequest;
use App\Http\Requests\UpdateUserPreferenceRequest;
use App\Models\UserPreference;

class UserPreferenceController extends BaseController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreUserPreferenceRequest $request)
    {
        //
    }

    /**
     * show
     *
     * @param  mixed $userPreference
     * @return void
     */
    public function show(UserPreference $userPreference)
    {
        //
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $userPreference
     * @return void
     */
    public function update(UpdateUserPreferenceRequest $request, UserPreference $userPreference)
    {
        //
    }

    /**
     * destroy
     *
     * @param  mixed $userPreference
     * @return void
     */
    public function destroy(UserPreference $userPreference)
    {
        //
    }
}
