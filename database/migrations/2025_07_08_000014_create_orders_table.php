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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('shipping_address_id')->nullable(false);
            $table->unsignedBigInteger('billing_address_id')->nullable(false);
            $table->unsignedBigInteger('shipping_method_id')->nullable(false);
            $table->decimal('total_price')->nullable(false);
            $table->unsignedBigInteger('status_id')->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('shipping_address_id')->on('addresses')->references('id');
            $table->foreign('billing_address_id')->on('billing_addresses')->references('id');
            $table->foreign('shipping_method_id')->on('shipping_methods')->references('id');
            $table->foreign('status_id')->on('statuses')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
