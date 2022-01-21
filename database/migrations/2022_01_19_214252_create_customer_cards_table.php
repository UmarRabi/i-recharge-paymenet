<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('card_no')->unique();
            $table->string('expiry_day');
            $table->string('expiry_year');
            $table->string('cvv', 3);
            $table->string('pin');
            $table->timestamps();
        });

       Schema::create('customer_transactions_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ref');
            $table->unsignedBigInteger('customer_id');
            $table->double('amount');
            $table->string('description');
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
        Schema::dropIfExists('customer_cards');
    }
}
