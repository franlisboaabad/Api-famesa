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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_id')->unique(); // ID de HGLevel
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('tags')->nullable();
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('timezone')->nullable();
            $table->timestamp('date_created')->nullable();
            $table->string('contact_source')->nullable();
            $table->text('full_address')->nullable();
            $table->string('contact_type')->nullable();
            $table->string('location_id')->nullable(); // FK a locations
            $table->string('invoice_id')->nullable(); // FK a invoices
            $table->string('workflow_id')->nullable(); // FK a workflows
            $table->json('custom_data')->nullable();
            $table->json('attribution_source')->nullable();
            $table->timestamps();

            $table->index(['contact_id']);
            $table->index(['email']);
            $table->index(['phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
