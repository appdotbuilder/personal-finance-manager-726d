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
        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['income', 'expense'])->comment('Transaction type');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->string('description');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly'])->comment('Recurrence frequency');
            $table->date('start_date')->comment('Start date for recurring transactions');
            $table->date('end_date')->nullable()->comment('End date for recurring transactions');
            $table->date('next_due_date')->comment('Next due date for transaction generation');
            $table->boolean('is_active')->default(true)->comment('Whether recurring transaction is active');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_active', 'next_due_date']);
            $table->index('next_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_transactions');
    }
};