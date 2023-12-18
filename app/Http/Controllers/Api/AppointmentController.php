<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ApiResponse;

class AppointmentController extends Controller
{
    // Book an appointment

    public function create(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'dog_item_id' => 'required|integer',
            'phone_number' => 'required|string',
            'service' => 'required|string',
            'date' => 'required|date',
            'hour' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return ApiResponse::badrequest($validator->errors());
        }

        // Check if an appointment with the same date and hour already exists
        $existingAppointment = Appointment::where('date', $request->input('date'))
            ->where('hour', $request->input('hour'))
            ->first();

        if ($existingAppointment) {
            return ApiResponse::badrequest('The appointment that has already been booked.');
        }

        // Check if the dog already has an active appointment
        $existingDogAppointment = Appointment::where('dog_item_id', $request->input('dog_item_id'))
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->first();

        if ($existingDogAppointment) {
            // Check if date and hour properties exist on the $existingDogAppointment object
            $date = $existingDogAppointment->date ?? 'unknown date';
            $hour = $existingDogAppointment->hour ?? 'unknown hour';

            $errorMessage = 'The dog is scheduled for an appointment on ' . $date . ' at ' . $hour . ':00.';
            return ApiResponse::badrequest($errorMessage);
        }

        // Get the authenticated user's ID
        $user_id = Auth::id();

        // Create a new appointment with user_id
        $appointment = Appointment::create([
            'user_id' => $user_id,
            'user_name' => $request->input('user_name'),
            'dog_item_id' => $request->input('dog_item_id'),
            'phone_number' => $request->input('phone_number'),
            'service' => $request->input('service'),
            'date' => $request->input('date'),
            'hour' => $request->input('hour'),
            'description' => $request->input('description'),
            'status' => 'Pending',
            'is_cancel' => false,
        ]);

        // Return a response indicating success
        return ApiResponse::created(['message' => 'Appointment booked successfully', 'data' => $appointment]);
    }
}
