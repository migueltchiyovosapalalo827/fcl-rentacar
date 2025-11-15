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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['aluguer','caucao','multa','outros'])->default('aluguer')->index();
            $table->dateTime('paid_at')->nullable();
            $table->enum('method', ['numerario','transferencia','pos','outros'])->default('numerario');
            $table->enum('status', ['pago','pendente','reembolsado'])->default('pendente')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
