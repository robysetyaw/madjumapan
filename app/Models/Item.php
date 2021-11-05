<?php

namespace App\Models;

use Carbon\Carbon;
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

    static public function get_transaction(Request $request)
    {

        $status = $request->status ?? NULL;

        $date1 = $request->date1;
        $date2 = $request->date2;
        $date_filter_type = $request->date_filter_type; // filter type "this_day', "yesterday", "tomorrow", "beetween"
        
        $per_person_id = $request->per_person_id; // customer id, supplier id, gudang id
        $per_person_type = $request->per_person_type ?? NULL; // filter type "customer", "supplier", "gudang"
        
        // this_day with status
        // query hari ini dengan status in atau out 
        if ($date_filter_type === "this_day" && $status !== NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.status='$status' AND DATE(it.created_at)=CURDATE()");
        }

        // this_day without status
        // query hari ini tanpa status
        elseif ($date_filter_type === "this_day") {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE DATE(it.created_at)=CURDATE()");
        }













        // between date with status with per person type
        // query diantara tanggal date 1 dan date 2 dengan status in atau out dan tipe orang customer
        elseif($date_filter_type === "between" && $per_person_type !== "customer" && $status !== NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.status='$status' AND  it.customer_id='$per_person_id'   AND (it.created_at BETWEEN '$date1' AND '$date2') ");
        }

         // between date with status with per person type
        // query diantara tanggal date 1 dan date 2 dengan status in atau out dan tipe orang supplier
        elseif($date_filter_type === "between" && $per_person_type !== "supplier" && $status !== NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.status='$status' AND  it.supplier_id='$per_person_id'   AND (it.created_at BETWEEN '$date1' AND '$date2') ");
        }

         // between date with status with per person type
        // query diantara tanggal date 1 dan date 2 dengan status in atau out dan tipe orang gudang
        elseif($date_filter_type === "between" && $per_person_type !== "gudang" && $status !== NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.status='$status' AND  it.gudang_id='$per_person_id'   AND (it.created_at BETWEEN '$date1' AND '$date2') ");
        }










         // between date without status with per person type
         // query diantara tanggal date 1 dan date 2, tanpa status,  dan tipe orang customer, 
        elseif($date_filter_type === "between" && $per_person_type !== "customer") {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.customer_id='$per_person_id' AND  (it.created_at BETWEEN '$date1' AND '$date2') ");
        }

        // between date without status with per person type
         // query diantara tanggal date 1 dan date 2, tanpa status,  dan tipe orang supplier, 
         elseif($date_filter_type === "between" && $per_person_type !== "supplier") {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.supplier_id='$per_person_id' AND  (it.created_at BETWEEN '$date1' AND '$date2') ");
        }
        // between date without status with per person type
         // query diantara tanggal date 1 dan date 2, tanpa status,  dan tipe orang gudang, 
         elseif($date_filter_type === "between" && $per_person_type !== "gudang") {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it 
            WHERE it.gudang
             _id='$per_person_id' AND  (it.created_at BETWEEN '$date1' AND '$date2') ");
        }
        
        
        
        
        
        
        
        // all day
        else {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at
            FROM items as it ");
        }
        

        return $db;
    }
}
