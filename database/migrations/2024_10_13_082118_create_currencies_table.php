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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->integer('cross_order')->nullable();
            $table->string('code');
            $table->integer('unit')->nullable();
            $table->string('name');
            $table->decimal('forex_buying', 10, 4)->nullable();
            $table->decimal('forex_selling', 10, 4)->nullable();
            $table->decimal('banknote_buying', 10, 4)->nullable();
            $table->decimal('banknote_selling', 10, 4)->nullable();
            $table->decimal('cross_rate_usd', 10, 4)->nullable();
            $table->decimal('cross_rate_other', 10, 4)->nullable();
            $table->date('date');

            $table->unique(['code', 'date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
