<?php

namespace App\Traits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Utils
{
    /**
     * successResponse
     *
     * @param  mixed $data
     * @param  mixed $message
     * @param  mixed $status
     * @return JsonResponse
     */
    public static function successResponse($data = [], string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }


    /**
     * errorResponse
     *
     * @param  mixed $errors
     * @param  mixed $message
     * @param  mixed $status
     * @return void
     */
    public static function errorResponse(array $errors, string $message = 'Error', int $status = 500)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $message,
                'errors'  => $errors,
            ], $status)
        );
    }
}
