<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($this->user()->id === (int) $this->profile->user_id) || (bool) $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'availability_id' => 'required|exists:availabilities,id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'avatar_image' => 'nullable|image|max:2048',
            'cover_photo_image' => 'nullable|image|max:4096',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'bio' => 'required|string|max:1000',
            'interests' => 'required|string|max:255',
            'relationship_status' => 'required|string|max:255,in:Single,In a relationship,In a open relationship,In more than one relationship,It\'s complicated,Married,Widowed,Divorced,Separated,dontwannasay',
            'sexual_orientation' => 'required|string|max:255|in:Lesbian,Gay,Bisexual,Transgender,Queer,Intersex,Asexual,+,dontwannasay,Hetero',
            'languages' => 'required|string|max:255',
        ];
    }
}
