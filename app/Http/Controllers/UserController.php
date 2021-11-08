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
            $token = $user->createToken($username_form)->plainTextToken;
            $context = [
                "status" => "success",
                'message' => [
                    "token" => $token, // return token
                    "is_admin" => $user->is_admin,
                    "is_gudang" => $user->is_gudang,
                    "is_supplier" => $user->is_supplier,
                    "is_customer" => $user->is_customer,
                ]
            ];
            $status = 200;
            return response($context, $status);
        }
    }
}
