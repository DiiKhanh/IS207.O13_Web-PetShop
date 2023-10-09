<?php
namespace App\Http\Responses;

use Illuminate\Http\Response;

class ApiResponse
{
    public static function error()
    {
        return self::responseWithData(500, [
            'status' => 500,
            'message' => 'Oops! Something wrong!'
        ]);
    }

    public static function badrequest($message)
    {
        return self::responseWithData(400, [
            'status' => 400,
            'message' => $message
        ]);
    }

    public static function ok($data)
    {
        return self::responseWithData(200, $data);
    }

    public static function created($data)
    {
        return self::responseWithData(201, $data);
    }

    public static function unauthorize()
    {
        return self::responseWithData(401, [
            'status' => 401,
            'message' => 'Unauthorized'
        ]);
    }

    public static function notfound()
    {
        return self::responseWithData(404, [
            'status' => 404,
            'message' => 'Resource not found'
        ]);
    }

    private static function responseWithData($statusCode, $data)
    {
        return response()->json($data, $statusCode);
    }
}