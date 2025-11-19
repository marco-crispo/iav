<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Events\UserStatusUpdated;
use App\Events\UserProfileUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends BaseController
{
    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $profiles = Profile::with('user')->paginate(20);
        return $this->successResponse($profiles->items(), 'Profiles retrieved successfully', Response::HTTP_OK);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(StoreProfileRequest $request): JsonResponse
    {
        $user = User::find($request->user_id);

        // In order to use r2 cloudflare
        // $this->ensureUploadsDirectoryExists();

        $pathAvatar = $request->file('avatar_image')->storeAs(
            'uploads',
            "avatar_{$user->id}" . '.' . $request->file('avatar_image')->getClientOriginalExtension(),
            'public' // config('filesystems.default') in order to use r2 cloudflare
        );
        $pathCoverPhoto = $request->file('cover_photo_image')->storeAs(
            'uploads',
            "cover_photo_{$user->id}" . '.' . $request->file('cover_photo_image')->getClientOriginalExtension(),
            'public' // config('filesystems.default') in order to use r2 cloudflare
        );

        $data = $request->validated();
        $data['avatar'] = $pathAvatar;
        $data['cover_photo'] = $pathCoverPhoto;

        $profile = Profile::create($data);
        event(new Registered($user));

        return $this->successResponse($profile, 'Profile created successfully', Response::HTTP_CREATED);
    }

    /**
     * show
     *
     * @param  mixed $profile
     * @return JsonResponse
     */
    public function show(Profile $profile): JsonResponse
    {
        return $this->successResponse($profile, 'Profile retrieved successfully', Response::HTTP_OK);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $profile
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $user = $request->user();

        if ($request->hasFile('avatar_image')) {
            $pathAvatar = $request->file('avatar_image')->storeAs(
                'uploads',
                "avatar_{$user->id}" . '.' . $request->file('avatar_image')->getClientOriginalExtension(),
                'public'
            );
        } else {
            $pathAvatar = $profile->avatar;
        }
        if ($request->hasFile('cover_photo_image')) {
            $pathCoverPhoto = $request->file('cover_photo_image')->storeAs(
                'uploads',
                "cover_photo_{$user->id}" . '.' . $request->file('cover_photo_image')->getClientOriginalExtension(),
                'public'
            );
        } else {
            $pathCoverPhoto = $profile->cover_photo;
        }

        $data = $request->validated();
        $data['avatar'] = $pathAvatar;
        $data['cover_photo'] = $pathCoverPhoto;

        $profile->update($data);

        if ($profile->wasChanged()) {
            if ($profile->wasChanged('availability_id')) {
                broadcast(new UserStatusUpdated($user));
            }
            broadcast(new UserProfileUpdated($user));
        }

        return $this->successResponse($profile, 'Profile updated successfully', Response::HTTP_OK);
    }

    /**
     * destroy
     *
     * @param  mixed $profile
     * @return JsonResponse
     */
    public function destroy(Profile $profile): JsonResponse
    {
        $profile->delete();
        return $this->successResponse(null, 'Profile deleted successfully', Response::HTTP_NO_CONTENT);
    }

    private function ensureUploadsDirectoryExists(): void
    {
        $disk = Storage::disk(config('filesystems.default'));
        if (!$disk->exists('uploads')) {
            $disk->makeDirectory('uploads');
        }
    }
}
