<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\UserFilter;

class UserFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // O gestisci autorizzazioni se necessario
    }

    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'filters' => ['nullable', 'array'],
            'filters.online' => ['nullable', 'numeric', 'in:0,1'],
            'filters.radiusKm' => ['nullable', 'numeric', 'min:1', 'max:50000'],
            'filters.status' => ['nullable', 'in:I feel lonely,I need to chat,Let\'s get to know each other,Buy me a coffee!,Surprise me!,Looking for something,Looking for something more,Shall we take a room?'],
            'filters.name' => ['nullable', 'string', 'max:255'],
            'filters.surname' => ['nullable', 'string', 'max:255'],
            'filters.city' => ['nullable', 'string', 'max:255'],
            'filters.country' => ['nullable', 'string', 'max:255'],
            'filters.age' => ['nullable', 'array'],
            'filters.age.lower' => ['nullable', 'numeric', 'min:18'],
            'filters.age.upper' => ['nullable', 'numeric', 'min:18'],
            'filters.bio' => ['nullable', 'string'],
            'filters.interests' => ['nullable', 'string'],
            'filters.relationship_status' => ['nullable', 'string', 'in:Single,In a relationship,In a open relationship,In more than one relationship,It\'s complicated,dontwannasay'],
            'filters.sexual_orientation' => ['nullable', 'string', 'in:Lesbian,Gay,Bisexual,Transgender,Queer,Intersex,Asexual,+,dontwannasay,Hetero'],
            'filters.languages' => ['nullable', 'string'],

        ];
    }

    /**
     * toUserFilter
     *
     * @return UserFilter
     */
    public function toUserFilter(): UserFilter
    {
        return new UserFilter(
            latitude: $this->input('latitude'),
            longitude: $this->input('longitude'),
            filters: $this->input('filters')
        );
    }
}
