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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->string('slug', 100)->nullable(false);
            $table->decimal('price')->nullable(false);
            $table->text('description')->nullable(false);
            $table->unsignedBigInteger('collection_id')->nullable(false);
            $table->unsignedBigInteger('type_id')->nullable(false);
            $table->timestamps();

            $table->foreign('collection_id')->on('collections')->references('id');
            $table->foreign('type_id')->on('types')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
