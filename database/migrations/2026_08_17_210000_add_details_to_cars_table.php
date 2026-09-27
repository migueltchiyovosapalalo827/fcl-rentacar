<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->text('description')->nullable()->after('image');
            $table->string('color')->nullable()->after('description');
            $table->string('category')->nullable()->after('color');
            $table->unsignedTinyInteger('seats')->nullable()->after('category');
            $table->unsignedTinyInteger('doors')->nullable()->after('seats');
            $table->unsignedTinyInteger('luggage_capacity')->nullable()->after('doors');
            $table->string('fuel_type')->nullable()->after('luggage_capacity');
            $table->string('transmission')->nullable()->after('fuel_type');
            $table->boolean('air_conditioning')->default(true)->after('transmission');
            $table->json('photos')->nullable()->after('air_conditioning');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'color',
                'category',
                'seats',
                'doors',
                'luggage_capacity',
                'fuel_type',
                'transmission',
                'air_conditioning',
                'photos',
            ]);
        });
    }
};
