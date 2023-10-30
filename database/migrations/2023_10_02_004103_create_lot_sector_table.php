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
        Schema::create('lot_sector', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lot_id');
            $table->unsignedBigInteger('sector_id');
            // Adicione outras colunas conforme necessário

            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('lot_id')
                ->references('id')
                ->on('lots')
                ->onDelete('cascade');
            $table->foreign('sector_id')
                ->references('id')
                ->on('sectors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_sector');
    }
};
