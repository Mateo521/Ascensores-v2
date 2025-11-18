<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elevators', function (Blueprint $table) {
            $table->unsignedInteger('max_capacity_kg')->nullable()->after('address');
            $table->boolean('fire_system')->default(false)->after('max_capacity_kg');
            $table->text('comments')->nullable()->after('fire_system');
        });
    }

    public function down(): void
    {
        Schema::table('elevators', function (Blueprint $table) {
            $table->dropColumn(['max_capacity_kg', 'fire_system', 'comments']);
        });
    }
};
