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
        Schema::create('party_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('party_id')->nullable();
            $table->string('company_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('rate')->nullable();
            $table->double('total_amount', 10, 2)->nullable();
            $table->double('credit', 10, 2)->nullable();
            $table->double('balance_amount', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_name')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_orders');
    }
};
