<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TokenValidationController extends Controller
{
    public function checkToken(Request $request) 
    {
        $token = $request->header('token');

        if ($token)
        {
            return response()->json(['message' => 'token receive sucessfully',
                                    'token' => $token

                    ], Response::HTTP_CREATED);
        }
        else
        {
            return response()->json(['message' => 'token not found or invalid token'], Response::HTTP_NOT_FOUND);
        }
    }
}
