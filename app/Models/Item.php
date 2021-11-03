<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    static public function insert_item(Request $request)
    {
        $user = $request->user();

        $item_name = $request->item_name;
        $item_weight = $request->item_weight;
        $item_price = $request->item_price;
        $status = $request->status;

        $supplier_id = $request->supplier_id ?? NULL;
        $customer_id = $request->customer_id ?? NULL;

        
        if ($user->is_gudang === 1 || $user->is_admin === 1) {
            $item = new Item;
            $item->gudang_id = $user->id;
            if ($supplier_id !== NULL) $item->supplier_id = $supplier_id;
            if ($customer_id !== NULL) $item->customer_id = $customer_id;
            $item->item_name = $item_name;
            $item->item_weight = $item_weight;
            $item->item_price = $item_price;
            $item->status = $status;
            $item->save();
            return $item;
        }
    }

    // static public function get_out_transaction()
    // {
    //     $db = DB::select("SELECT 
    //     it.id, it.item_name, it.customer_id, it.item_weight, it.item_price ,
    //      (it.item_price *  it.item_weight) as 'total price', it.status
    //     FROM item as it 
    //     WHERE it.status='out' ");
        
    //     return $db;
    // }
}
