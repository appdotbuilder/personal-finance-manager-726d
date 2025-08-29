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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['debt', 'receivable'])->comment('Debt type - money owed by user or owed to user');
            $table->string('person_name')->comment('Name of person involved');
            $table->decimal('amount', 15, 2)->comment('Total debt amount');
            $table->decimal('paid_amount', 15, 2)->default(0)->comment('Amount already paid');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable()->comment('Due date for payment');
            $table->boolean('is_paid')->default(false)->comment('Whether debt is fully paid');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'type', 'is_paid']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};