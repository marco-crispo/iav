<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Events\UserLogout;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Exception;

/**
 * AuthController
 */
class AuthController extends BaseController
{

    /**
     * register
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function register(StoreUserRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'password' => bcrypt($request->password),
        ]);

        return $this->successResponse(['user' => $user], 'User registered successfully', Response::HTTP_CREATED);
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if (!Auth::attempt($data) || !Auth::user()) {
            $this->errorResponse(['Unauthorized'], 'Error', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::findOrFail(Auth::user()->id);
        $token = $user->createToken('auth_token')->plainTextToken;

        if (!$user->hasVerifiedEmail()) {
            return $this->successResponse(['message' => 'Unverified email', 'user' => $user, 'location' => $user->location, 'profile' => $user->profile, 'token' => $token], 'Unverified email', Response::HTTP_OK);
        }

        return $this->successResponse(['user' => $user, 'location' => $user->location, 'profile' => $user->profile, 'token' => $token], 'User logged in successfully', Response::HTTP_OK);
    }

    /**
     * emailIsVerified
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function isEmailVerified() : JsonResponse
    {
        if (!Auth::user()) {
            $this->errorResponse(['Unauthorized'], 'Error', Response::HTTP_UNAUTHORIZED);
        }
        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasVerifiedEmail()) {
            $this->errorResponse(['Email is not verified'], 'Error', Response::HTTP_OK);
        }

        return $this->successResponse([], 'Email is verified', Response::HTTP_OK);
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function logout() : JsonResponse
    {
        $user = Auth::user();
        broadcast(new UserLogout($user))->toOthers();

        // use the already retrieved $user instance to delete tokens
        if ($user) {
            $user->tokens()->delete();
        }

        return $this->successResponse(['message' => 'Logged out successfully'], 'User logged out successfully', Response::HTTP_OK);
    }
    /**
     * user
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function user(Request $request) : JsonResponse
    {
        $user = User::with('profile')->find($request->user()->id);

        if (!$user) {
            $this->errorResponse(['User not found'], 'Error', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse($user, 'User retrieved successfully', Response::HTTP_OK);
    }

    /**
     * refresh
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function refresh() : JsonResponse
    {
        $user = User::findOrFail(Auth::user()->id);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(['user' => $user, 'token' => $token], 'Token refreshed successfully', Response::HTTP_OK);
    }

    /**
     * forgotPassword
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request) : JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|string|email',
        ]);

        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->errorResponse(['message' => __($status)], 'Unable to send password reset link', Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse(['message' => __($status)], 'Password reset link sent successfully', Response::HTTP_OK);
    }

    /**
     * resetPassword
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request) : JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        $status = Password::reset(
            $data,
            function ($user, $password) {
                // Aggiorna la password
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->errorResponse(['message' => __($status)], 'Password reset failed', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(['message' => __($status)], 'Password reset successfully', Response::HTTP_OK);
    }
}
