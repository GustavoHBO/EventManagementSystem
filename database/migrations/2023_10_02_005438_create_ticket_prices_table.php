<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('lot_id');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('sector_id')
                ->references('id')
                ->on('sectors')
                ->onDelete('cascade');
            $table->foreign('lot_id')
                ->references('id')
                ->on('lots')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_prices');
    }
};
