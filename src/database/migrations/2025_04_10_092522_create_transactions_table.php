<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->enum('status', ['chatting', 'completed', 'canceled'])->default('chatting');
            $table->boolean('seller_rated')->default(false);
            $table->boolean('buyer_rated')->default(false);
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
        Schema::dropIfExists('transactions');
    }
}
