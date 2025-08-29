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
        Schema::create('savings_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('target_amount', 15, 2)->comment('Target savings amount');
            $table->decimal('current_amount', 15, 2)->default(0)->comment('Current saved amount');
            $table->date('target_date')->nullable()->comment('Target completion date');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false)->comment('Whether goal is completed');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_completed']);
            $table->index('target_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_goals');
    }
};