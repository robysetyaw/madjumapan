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

    static public function get_stocks(Request $request)
    {
  

        $db = DB::select("SELECT its.item_name, 
        SUM(CASE
            WHEN it.status = 'in' THEN it.item_weight 
            WHEN it.status IS NULL THEN 0
            ELSE 0 END) -  SUM(CASE WHEN it.status = 'out' THEN it.item_weight ELSE 0 END) AS 'stock'

        FROM item_names AS its 
        LEFT JOIN items as it  ON its.item_name=it.item_name
        GROUP BY its.item_name;");
        return $db;
        
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
            if ($status === "out") {
                $db = DB::select("SELECT
                SUM(CASE WHEN it.status = 'in' THEN it.item_weight ELSE 0 END) - 
                SUM(CASE WHEN it.status = 'out' THEN it.item_weight ELSE 0 END) AS 'stock'
                FROM items as it
                WHERE it.item_name='$item_name' ");
                $s = $db[0]->stock;
                if ($s < $item_weight) {
                    return array("error", "Berat barang yang diinputkan lebih besar dari stock, karena stock $s dan input $item_weight");
                }
            }
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
        
        $per_person_id = $request->per_person_id ?? NULL; // customer id, supplier id, gudang id
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

        






        elseif($date_filter_type === "one_day" && $per_person_type === "gudang" && $status !== NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, 
            it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE it.status='$status' AND us.id='$per_person_id' AND
            us.is_gudang='1' AND us.is_admin='0' AND DATE(it.created_at) = '$date2' ");
        }
        elseif($date_filter_type === "one_day" && $per_person_type === "gudang" && $status === NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE us.id='$per_person_id' AND us.is_gudang='1' AND us.is_admin='0' 
            AND DATE(it.created_at) = '$date2' ");
        }
        elseif($date_filter_type === "between" && $per_person_type ===  "gudang" && $status !== NULL ) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE it.status='$status'  AND us.id='$per_person_id' AND
            us.is_gudang='1' AND us.is_admin='0' AND DATE(it.created_at) BETWEEN '$date1' AND '$date2' ");
        }
        elseif($date_filter_type === "between" && $per_person_type === "gudang" ) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE  us.id='$per_person_id' AND us.is_gudang='1' AND 
            us.is_admin='0' AND DATE(it.created_at) BETWEEN '$date1' AND '$date2' ");
        }
        










        elseif($date_filter_type === "one_day"  && $per_person_type === NULL && $status !== NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id,  it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE it.status='$status' AND DATE(it.created_at) = '$date2' ");
        }

        elseif($date_filter_type === "one_day"  && $per_person_type === NULL && $status === NULL) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE DATE(it.created_at) = '$date2' ");
        }

        elseif($date_filter_type === "between" && $per_person_type === NULL && $status !== NULL ) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE it.status='$status' AND DATE(it.created_at) BETWEEN '$date1' AND '$date2' ");
        }

       
        elseif($date_filter_type === "between" && $per_person_type === NULL ) {
            $db = DB::select("SELECT 
            it.id, it.gudang_id, us.name AS 'name_gudang', it.customer_id, it.supplier_id,  it.item_name, it.item_weight, it.item_price ,
            (it.item_price *  it.item_weight) as 'total price', it.status, it.created_at,
            cu.name AS 'name_customer', su.name AS 'name_supplier'
            FROM items as it 
            INNER JOIN users as us ON it.gudang_id=us.id
            LEFT JOIN users as cu ON it.customer_id=cu.id 
            LEFT JOIN users as su ON it.supplier_id=su.id
            WHERE DATE(it.created_at) BETWEEN '$date1' AND '$date2' ");
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

    static public function get_item_weight(Request $request)
    {
        $date1 = $request->date1;
        $item_name = $request->item_name;
        $db = DB::select(
            "SELECT 
            SUM(CASE
            WHEN it.status = 'in' THEN it.item_weight 
            WHEN it.status IS NULL THEN 0
            ELSE 0 END)  AS 'stock_in',

            SUM(CASE 
            WHEN it.status = 'out' THEN it.item_weight 
            WHEN it.status IS NULL THEN 0
            ELSE 0 END)  AS 'stock_out' FROM item_names AS its 
            LEFT JOIN items as it  ON its.item_name=it.item_name
            WHERE it.item_name = '$item_name' AND  DATE(it.created_at) = '$date1'
            GROUP BY its.item_name;"
        );
        // SELECT its.item_name, 
        // SUM(CASE
        //     WHEN it.status = 'in' THEN it.item_weight 
        //     WHEN it.status IS NULL THEN 0
        //     ELSE 0 END) -  SUM(CASE WHEN it.status = 'out' THEN it.item_weight ELSE 0 END) AS 'stock'

        // FROM item_names AS its 
        // LEFT JOIN items as it  ON its.item_name=it.item_name
        // GROUP BY its.item_name;
        return $db;
    }
}
