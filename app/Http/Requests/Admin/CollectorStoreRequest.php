<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for collector creation
 * Preserves exact same validation rules as before
 */
class CollectorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        // Exact same validation rules as before
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'contact'  => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ];
    }
}