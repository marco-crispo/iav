<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($this->userPosition->user_id === $this->user()->id) || (bool) $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            // 'distance' => 'sometimes|numeric|min:0',
            // 'status' => 'nullable|string|in:I feel lonely,I need to chat,Let\'s get to know each other,Buy me a coffee!,Surprise me!,Looking for something,Looking for something more,Shall we take a room?',
        ];
    }
}



