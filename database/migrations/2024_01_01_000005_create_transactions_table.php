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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->enum('type', ['income', 'expense', 'transfer'])->comment('Transaction type');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->string('description');
            $table->date('date')->comment('Transaction date');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'date']);
            $table->index(['account_id', 'type']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};