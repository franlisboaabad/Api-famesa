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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id'); // FK a invoices
            $table->string('item_id')->unique(); // ID del item en HGLevel
            $table->string('product_id')->nullable();
            $table->string('price_id')->nullable();
            $table->string('currency', 3);
            $table->string('name');
            $table->integer('qty');
            $table->decimal('amount', 15, 4);
            $table->text('description')->nullable();
            $table->boolean('tax_inclusive')->default(true);
            $table->json('taxes')->nullable();
            $table->timestamps();

            $table->index(['invoice_id']);
            $table->index(['item_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
