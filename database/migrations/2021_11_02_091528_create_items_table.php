<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            
            $table->unsignedBigInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('users');

            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('users');

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');
          

            $table->string("item_name");
            $table->decimal("item_weight");
            $table->integer( 'item_buy_price');
            $table->integer( 'item_sell_price');
            $table->string("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
