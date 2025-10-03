<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * BookingRequest handles validation for booking operations
 * Includes manual capacity validation as required by the assignment
 */
class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAttendee();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $event = \App\Models\Event::find($this->input('event_id'));
            
            if ($event) {
                // Manual capacity validation as required by assignment
                $currentBookings = $event->bookings()->where('status', 'confirmed')->count();
                
                if ($currentBookings >= $event->capacity) {
                    $validator->errors()->add('event_id', 'This event is full.');
                }

                // Check if user already booked this event
                if ($event->isBookedBy(auth()->id())) {
                    $validator->errors()->add('event_id', 'You have already booked this event.');
                }

                // Check if event is in the past
                if ($event->date < now()->toDateString()) {
                    $validator->errors()->add('event_id', 'Cannot book past events.');
                }
            }
        });
    }
}
