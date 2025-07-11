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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable(false);
            $table->string('payment_method', 100)->nullable(false)->nullable(false);
            $table->unsignedBigInteger('payment_status_id')->nullable(false);
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('payment_status_id')->on('statuses')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
