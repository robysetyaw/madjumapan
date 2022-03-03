<?php

namespace App\Models;

use Database\Seeders\items;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    static public function get_transactions(Request $request)
    {
        $db = DB::select("SELECT DISTINCT trx.id,payment,customer_id,us.name,status 
        FROM transactions trx LEFT JOIN users us ON trx.customer_id=us.id;");
        return $db;
    }

    static public function insert_transaction(Request $request)
    {
        $user = $request->user();

        $stts = 0;
        // $status = $request->status;
        $customer_id = $request->customer_id ?? NULL;

        if ($user->is_gudang === 1 || $user->is_admin === 1) {
            $transaction = new Transaction();

            if ($customer_id !== NULL) $transaction->customer_id = $customer_id;
            $transaction->status = $stts;

            $transaction->save();
            return $transaction;
        }

    }

    static public function update_transaction($id)  
    {
        $transaction = Transaction::find($id);
        
        $payment = DB::select("SELECT cash FROM payments pay 
        LEFT JOIN transactions trx ON pay.transactions_id=trx.id
        WHERE transactions_id=$id;");
        return $payment;

        $item = DB::select("SELECT item_price FROM items itm 
        LEFT JOIN transactions trx ON itm.transactions_id=trx.id
        WHERE transactions_id=$id;");
        return $item;

        if ($payment < $item ) {
            $status = 0;
        }else{
            $status = 1;
        }
        return $status;

        $transaction = DB::table('post')
                        ->where($id)
                        ->update(['payment' => $payment,
                                    'status' => $status]);
        return $transaction;
    }
}
