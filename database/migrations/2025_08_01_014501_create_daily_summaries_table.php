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
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('equity', 12, 2)->default(0);
            $table->decimal('drawdown', 5, 2)->default(0); // porcentaje
            $table->decimal('deposits', 12, 2)->default(0);
            $table->decimal('withdrawals', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_summaries');
    }
};
