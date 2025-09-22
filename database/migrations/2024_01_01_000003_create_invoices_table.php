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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique(); // ID de HGLevel
            $table->string('alt_id')->nullable();
            $table->string('alt_type')->nullable();
            $table->string('company_id')->nullable();
            $table->string('name');
            $table->string('invoice_number');
            $table->string('currency', 3);
            $table->string('status');
            $table->decimal('amount_paid', 15, 4)->default(0);
            $table->decimal('total', 15, 4);
            $table->decimal('invoice_total', 15, 4);
            $table->decimal('amount_due', 15, 4);
            $table->string('title')->nullable();
            $table->timestamp('issue_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->text('terms_notes')->nullable();
            $table->boolean('live_mode')->default(true);
            $table->boolean('deleted')->default(false);
            $table->json('business_details')->nullable();
            $table->json('contact_details')->nullable();
            $table->json('discount')->nullable();
            $table->json('payment_methods')->nullable();
            $table->json('configuration')->nullable();
            $table->json('late_fees_configuration')->nullable();
            $table->json('reminders_configuration')->nullable();
            $table->json('meta')->nullable();
            $table->json('total_summary')->nullable();
            $table->string('invoice_url')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('sent_by')->nullable();
            $table->json('sent_from')->nullable();
            $table->json('sent_to')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->index(['invoice_id']);
            $table->index(['invoice_number']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
