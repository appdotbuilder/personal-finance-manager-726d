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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['bank', 'e_wallet', 'cash', 'credit_card', 'investment'])->comment('Account type');
            $table->decimal('balance', 15, 2)->default(0)->comment('Current account balance');
            $table->string('currency', 3)->default('USD')->comment('Currency code');
            $table->text('description')->nullable()->comment('Account description');
            $table->boolean('is_active')->default(true)->comment('Whether account is active');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};