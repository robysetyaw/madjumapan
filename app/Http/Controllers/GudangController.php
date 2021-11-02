<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function insert_item_for_gudang_user(Request $request)
    {
        $gudang_id = $request->gudang_id;
        
        $supplier_id = $request->supplier_id;

        $customer_id = $request->customer_id;

        $item_name = $request->item_name;
        $item_weight = $request->item_weight;
        $item_buy_price = $request->item_buy_price;
        $item_sell_price = $request->item_sell_price;
        $status = $request->status;


        $context = [
            'user' => $request->user('api')
        ];
        $status = 200;
        return response($context,$status);
    }
}
