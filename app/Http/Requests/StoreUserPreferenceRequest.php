<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreUserPreferenceRequest
 */
class StoreUserPreferenceRequest extends FormRequest
{
    /**
     * authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * rules
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
