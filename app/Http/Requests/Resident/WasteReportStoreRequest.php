<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class WasteReportStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'resident';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'report_date' => 'required|date|before_or_equal:today',
            'waste_type' => 'required|string|in:organic,recyclable,hazardous,general,electronics,construction',
            'additional_details' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'location.required' => 'Location is required.',
            'latitude.required' => 'Location coordinates are required.',
            'longitude.required' => 'Location coordinates are required.',
            'latitude.between' => 'Invalid latitude value.',
            'longitude.between' => 'Invalid longitude value.',
            'report_date.required' => 'Report date is required.',
            'report_date.before_or_equal' => 'Report date cannot be in the future.',
            'waste_type.required' => 'Please select a waste type.',
            'waste_type.in' => 'Invalid waste type selected.',
            'additional_details.max' => 'Additional details must not exceed 1000 characters.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be JPEG, PNG, or JPG format.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'waste_type' => 'waste type',
            'additional_details' => 'additional details',
            'report_date' => 'report date',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for nullable fields
        if ($this->additional_details === '') {
            $this->merge(['additional_details' => null]);
        }
    }
}
