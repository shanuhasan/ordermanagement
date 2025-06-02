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
        Schema::create('master_order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('master_order_id')->nullable();
            $table->string('company_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->double('amount', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_order_details');
    }
};
