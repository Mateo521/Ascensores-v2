<?php

// database/migrations/2025_01_01_000002_create_revisions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elevator_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');  
            $table->unsignedTinyInteger('month');  
            $table->boolean('checked')->default(false);
            $table->timestamp('checked_at')->nullable();
            $table->foreignId('technician_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['elevator_id', 'year', 'month']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('revisions');
    }
};
