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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('code', 7)->unique();
            $table->decimal('discount_percentage', 5);
            $table->integer('max_usages')->nullable();
            $table->date('expiration_date')->nullable();
            $table->unsignedBigInteger('user_id'); // Adicionando a coluna para o usuário criador.
            $table->timestamps();

            $table->unique(['event_id', 'code']);

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users'); // Definindo a chave estrangeira para o usuário criador.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
