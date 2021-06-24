<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginJwtController extends Controller
{

    public  function login(Request $request)
    {


        $credentials = $request->all(['email', 'password']);

        Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string',
        ])->validate();

        if(!$token = auth('api')->attempt($credentials)){

            return response()->json(['errors' => 'Não Autorizado'], 401);
        }
        return response()->json([
            'token' => $token
        ]);
    }

    public function logout()
    {

            auth('api')->logout();

            return response()->json(['message'=> 'logout sucessfully!'], 200);
    }

    public function refresh()
    {

        $token = auth('api')->refresh();
        return response()->json([
            'token' => $token
        ]);
    }
}
