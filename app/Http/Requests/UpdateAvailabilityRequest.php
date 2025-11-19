<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()->is_admin || $this->user()->id === (int) $this->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
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
