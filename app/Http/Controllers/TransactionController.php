<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{   
     public function insert_transactions(Request $request)
    {

        $user = $request->user();
        if ($user->is_gudang === 1 || $user->is_admin === 1) {
            $transactions = Transaction::insert_transaction($request);
            if ($transactions[0] === "error") {
                $context = [
                    'status' => 'failed',
                    'message' => $transactions[1]
                    ,
                ];
                $status = 400;
                return response($context, $status);
            }
            else {
                $context = [
                    'status' => 'success',
                    'message' => [
                        'data' => $transactions
                    ]
                ];
                $status = 201;
                return response($context, $status);
            }
           
        } else {
            $context = [
                'status' => 'failed',
                'message' => 'invalid credential',
                // 'debug' => $user
            ];
            $status = 403;
            return response($context, $status);
        }
    }

    public function get_transactions(Request $request)
    {

        $transactions = Transaction::get_transactions($request);
        $context = [
            'status' => 'success',
            'message' => [
                'data' => $transactions
            ]
        ];
        $status = 200;
        return response($context, $status);
    }
}
