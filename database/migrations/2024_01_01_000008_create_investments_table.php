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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['stocks', 'bonds', 'crypto', 'real_estate', 'mutual_funds', 'other'])->comment('Investment type');
            $table->decimal('initial_value', 15, 2)->comment('Initial investment amount');
            $table->decimal('current_value', 15, 2)->comment('Current investment value');
            $table->date('purchase_date')->comment('Date of investment purchase');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'type']);
            $table->index('purchase_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};