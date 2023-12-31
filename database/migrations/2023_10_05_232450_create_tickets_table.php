<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('ticket_price_id');
            $table->unsignedBigInteger('order_item_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('ticket_statuses');
            $table->foreign('ticket_price_id')->references('id')->on('ticket_prices');
            $table->foreign('order_item_id')->references('id')->on('order_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
