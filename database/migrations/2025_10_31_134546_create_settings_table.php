<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Ascensores Nuevo Cuyo');
            $table->string('cuit')->nullable();
            $table->date('start_of_activity')->nullable();
            $table->string('emergency_phone')->default('2664555572');
            $table->string('logo_path')->nullable(); // storage/app/public/logo.png
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
