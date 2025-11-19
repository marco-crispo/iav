<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    /**
     * authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool) $this->user()->is_admin;
    }

    /**
     * rules
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|max:255,in:I feel lonely,I need to chat,Let\'s get to know each other,Buy me a coffee!,Surprise me!,Looking for something,Looking for something more,Shall we take a room?',
            'description' => 'required|string|max:1000',
            'active' => 'required|boolean',
        ];
    }
}
