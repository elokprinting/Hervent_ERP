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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->restrictOnDelete();
            $table->foreignId('previous_quotation_id')->nullable()->constrained('quotations')->restrictOnDelete();
            $table->unsignedInteger('revision');
            $table->string('status', 20)->default('draft');
            $table->string('currency', 3)->default('IDR');
            $table->string('customer_name');
            $table->string('customer_company')->nullable();
            $table->string('customer_contact', 120)->nullable();
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sent_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sent_to_email')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['lead_id', 'revision']);
            $table->index(['lead_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
