<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * EventRequest handles validation for event creation and updates
 * Implements comprehensive server-side validation as required
 */
class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isOrganiser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required.',
            'title.max' => 'Event title cannot exceed 100 characters.',
            'date.required' => 'Event date is required.',
            'date.after_or_equal' => 'Event date must be today or in the future.',
            'time.required' => 'Event time is required.',
            'location.required' => 'Event location is required.',
            'location.max' => 'Event location cannot exceed 255 characters.',
            'capacity.required' => 'Event capacity is required.',
            'capacity.min' => 'Event capacity must be at least 1.',
            'capacity.max' => 'Event capacity cannot exceed 1000.',
        ];
    }
}
