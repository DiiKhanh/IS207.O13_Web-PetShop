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
            'date' => 'required|string',
            'hour' => 'required|string',
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

    public function cancel($id, Request $request)
    {
        //Pending->Cancelled:Lịch bị huỷ bởi người dùng (Phải ghi rõ lí do huỷ lịch)
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return ApiResponse::notfound();
        }

        // Check if the appointment is in 'Pending' status
        if ($appointment->status !== 'Pending') {
            return ApiResponse::badrequest('Cannot cancel the appointment. Status is not Pending.');
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'result' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errorMessage = 'A reason is required to cancel the appointment.';
            return ApiResponse::badrequest($errorMessage);
        }

        // Update the 'is_cancel' field to true
        $appointment->update(['is_cancel' => true]);
        $appointment->update(['status' => 'Cancelled']);
        //Result is the reason Why user want to cancel
        $appointment->update(['result' => $request->input('result')]);
        return ApiResponse::ok(['message' => 'Appointment cancelled successfully', 'data' => $appointment]);
    }


    public function index()
    {
        $user = auth()->user();
        if ($user->role == "admin") {
            $appointments = Appointment::all();
        } else if ($user->role == "user") {
            $appointments = Appointment::where('user_id', $user->id)->get();
        }

        if ($appointments->isEmpty()) {
            return ApiResponse::notfound();
        } else {
            return ApiResponse::ok($appointments);
        }
    }

    //Pending: chờ duyệt
    //Approved: Đã Duyệt chờ đi khám
    //Completed: Đã đi khám có kết quả
    //Pending->Rejected:Admin không duyệt lịch (Phải ghi rõ lí do huỷ lịch)
    public function updateStatus($appointmentId, Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Pending,Approved,Rejected,Completed,Cancelled',
            'result' => ($request->input('status') == 'Completed' || $request->input('status') == 'Rejected') ? 'required|string' : '',
        ]);

        // If validation fails, return the errors using ApiResponse
        if ($validator->fails()) {
            return ApiResponse::badrequest($validator->errors());
        }

        $appointment = Appointment::find($appointmentId);

        // Check if the appointment exists
        if (!$appointment) {
            return ApiResponse::notfound();
        }

        // Update the appointment status and result
        $appointment->status = $request->input('status');
        $appointment->result = $request->input('result', null); // Set result if provided, otherwise set to null
        $appointment->save();

        // Return a response indicating success
        return ApiResponse::ok(['message' => 'The appointment has been updated', 'data' => $appointment]);
    }

    public function updateDateAndHour($appointmentId, Request $request)
    {
        $appointment = Appointment::find($appointmentId);
        if ($request->has('date') && $request->has('hour')) {
            $existingAppointment = Appointment::where('date', $request->input('date'))
                ->where('hour', $request->input('hour'))
                ->first();

            if ($existingAppointment) {
                return ApiResponse::badrequest('The appointment that has already been booked.');
            }

            $appointment->date = $request->input('date');
            $appointment->hour = $request->input('hour');
            return ApiResponse::ok(['message' => 'The appointment has been updated', 'data' => $appointment]);
        }
    }
}
