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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable(false);
            $table->unsignedBigInteger('product_variant_id')->nullable(false);
            $table->integer('quantity')->nullable(false);
            $table->decimal('price_at_purchase')->nullable(false);
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('product_variant_id')->on('product_variants')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
