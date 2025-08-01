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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->bigInteger('ticket')->unique();
            $table->string('symbol');
            $table->enum('type', ['buy', 'sell']);
            $table->decimal('lots', 10, 2);
            $table->timestamp('open_time');
            $table->timestamp('close_time')->nullable();
            $table->decimal('open_price', 12, 5);
            $table->decimal('close_price', 12, 5)->nullable();
            $table->decimal('profit', 12, 2)->default(0);
            $table->decimal('swap', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->integer('magic_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
