<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * WaitlistRequest handles validation for waitlist operations
 * Ensures proper waitlist management with business logic validation
 */
class WaitlistRequest extends FormRequest
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
                // Can only join waitlist if event is full
                if (!$event->isFull()) {
                    $validator->errors()->add('event_id', 'Cannot join waitlist for events that are not full.');
                }

                // Cannot join waitlist if already booked
                if ($event->isBookedBy(auth()->id())) {
                    $validator->errors()->add('event_id', 'You have already booked this event.');
                }

                // Cannot join waitlist if already on waitlist
                if ($event->isWaitlistedBy(auth()->id())) {
                    $validator->errors()->add('event_id', 'You are already on the waitlist for this event.');
                }

                // Check if event is in the past
                if ($event->date < now()->toDateString()) {
                    $validator->errors()->add('event_id', 'Cannot join waitlist for past events.');
                }
            }
        });
    }
}
