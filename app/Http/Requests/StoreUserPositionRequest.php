<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserPositionRequest extends FormRequest
{
    /**
     * authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->id === (int) $this->user_id;
    }

    /**
     * rules
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }
}
