<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Item;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function insert_item(Request $request)
    {

        $user = $request->user();
        if ($user->is_gudang === 1 || $user->is_admin === 1) {

            $item = Item::insert_item($request);
            $context = [
                'status' => 'success',
                'message' => [
                    'data' => $item
                ]
            ];
            $status = 201;
            return response($context, $status);
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

    public function get_transaction(Request $request)
    {
        
      
        $items = Item::get_transaction($request);
        $context = [
            'status' => 'success',
            'message' => [
                'data' => $items
            ]
            ];
        $status = 200;
        return response($context, $status);
    }

    public function get_stocks(Request $request)
    {
        $items = Item::get_stocks($request);
        $context = [
            'status' => 'success',
            'message' => [
                'data' => $items
            ]
            ];
        $status = 200;
        return response($context, $status);
    }
}
