<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $username_form = $request->username;
        $password_form = $request->password;
        $user = User::login($username_form, $password_form, $request);
        if ($user == "password invalid" || $user == "username not found") {
            $context = [
                "status" => "failed",
                'message' => [
                    'message_failed' => $user
                ]
            ];
            $status = 401;
            return response($context, $status);
        }
        else {
            
            $context = [
                "status" => "success",
                'message' => [
                    "token" => $user // return token
                ]
            ];
            $status = 200;
            return response($context, $status);
        }
    }
}
