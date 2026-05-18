<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Form Request for storing glamping bookings.
 * Validates all booking data before processing.
 */
class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public booking allowed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isAuthenticated = Auth::check();

        return [
            'unit_id' => ['required', 'integer', 'exists:area_units,id'],
            'checkin' => ['required', 'date', 'after_or_equal:today'],
            'checkout' => ['required', 'date', 'after:checkin'],
            'guestCount' => ['required', 'integer', 'min:1', 'max:20'],
            'name' => [$isAuthenticated ? 'nullable' : 'required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
            'email' => [$isAuthenticated ? 'nullable' : 'required', 'email', 'max:255'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'min:0'],
            'extra_charge_mode' => ['nullable', 'in:full,breakfast'],
            'agree' => ['accepted'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'unit_id.required' => 'Please select a unit/deck first.',
            'unit_id.exists' => 'The selected unit is invalid.',
            
            'checkin.required' => 'Check-in date is required.',
            'checkin.date' => 'Check-in date format is invalid.',
            'checkin.after_or_equal' => 'Check-in date cannot be in the past.',
            
            'checkout.required' => 'Check-out date is required.',
            'checkout.date' => 'Check-out date format is invalid.',
            'checkout.after' => 'Check-out date must be after check-in.',
            
            'guestCount.required' => 'Number of guests is required.',
            'guestCount.integer' => 'Number of guests must be a number.',
            'guestCount.min' => 'Minimum 1 guest.',
            'guestCount.max' => 'Maximum 20 guests per booking.',
            
            'name.required' => 'Full name is required.',
            'name.max' => 'Name maximum 255 characters.',
            
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number minimum 8 digits.',
            'phone.max' => 'Phone number maximum 20 digits.',
            
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            
            'agree.accepted' => 'You must agree to the terms and conditions.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'unit_id' => 'unit',
            'checkin' => 'check-in date',
            'checkout' => 'check-out date',
            'guestCount' => 'number of guests',
            'name' => 'name',
            'phone' => 'phone number',
            'email' => 'email',
            'agree' => 'agreement',
        ];
    }
}
