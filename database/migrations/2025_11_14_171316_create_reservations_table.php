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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('pickup_location_id')->constrained('locations')->restrictOnDelete();
            $table->foreignId('dropoff_location_id')->constrained('locations')->restrictOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('purpose', ['negocios','casamento','passeio','trabalho','outros'])->default('outros');
            $table->boolean('with_driver')->default(false);
            $table->enum('status', ['pendente','confirmada','ativa','concluida','cancelada'])->default('pendente')->index();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
