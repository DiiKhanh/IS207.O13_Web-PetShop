<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MailCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function checkout(Request $request)
    {
        try{
            $request->validate([
                'total' => 'required|numeric',
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|string|email|max:255',
                'phone' => 'required|min:6',
            ]);
            $email = $request->input('email');
            $total = $request->input('total');
            $name = $request->input('name');
            $address = $request->input('address');
            $phone = $request->input('phone');

            Mail::to($email)->send(new MailCheckout($name, $phone, $total, $address));
            return response()->json(['status' => 200, 'data' => 'Đã gửi email thành công! Vui lòng kiểm tra']);
        } catch (\Throwable $th)
        {
            return response()->json($th->getMessage(), 500);
        }
    }
}
