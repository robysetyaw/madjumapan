<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function insert_user(Request $request)
    {
        $user = User::insert_user($request);
        $context = [
            'status' => 'success',
            'message' => $user
        ];
        $status = 201;
        return response($context, $status);
    }
    public function login(Request $request)
    {
        $username_form = $request->username;
        $password_form = $request->password;
        $user = User::login($username_form, $password_form, $request);
        if ($user == "password invalid" || $user == "username not found") {
            $context = [
                "status" => "failed",
                'message' => $user
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

    public function get_user_names(Request $request)
    {
        $role = $request->role;
        if ($role === "supplier") {
            $db = DB::select(
                "SELECT us.username, us.id, us.name FROM users AS us 
                WHERE us.is_supplier = '1'"
            );
        } else if($role ==="customer") {
            $db = DB::select(
                "SELECT us.username, us.id,us.name FROM users AS us 
                WHERE us.is_customer = '1'"
            );
        } else if ($role === "gudang") {
            $db = DB::select(
                "SELECT us.username, us.id,us.name FROM users AS us 
                WHERE us.is_gudang = '1' AND us.is_admin='0'"
            );
        }
        
        $context = [
            "status" => "success",
            'message' => [
                'data' => $db
            ]
        ];
        $status = 200;
        return response($context, $status);
    }
}
