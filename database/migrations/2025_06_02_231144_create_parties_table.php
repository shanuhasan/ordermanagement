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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('guid')->nullable();
            $table->string('company_id')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->longText('address')->nullable();
            $table->string('email')->nullable()->unique();
            $table->tinyInteger('is_deleted')->default(0)->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
