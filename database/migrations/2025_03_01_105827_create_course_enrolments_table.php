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
        Schema::create('course_enrolments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_email');
            $table->string('billing_contact_number');
            $table->text('billing_address');
            $table->string('billing_city');
            $table->string('billing_state')->nullable();
            $table->string('billing_country');
            $table->decimal('course_price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('grand_total', 10, 2);
            $table->string('currency_text')->nullable();
            $table->enum('currency_text_position', ['left', 'right'])->nullable();
            $table->string('currency_symbol')->nullable();
            $table->enum('currency_symbol_position', ['left', 'right'])->nullable();
            $table->string('payment_method')->nullable();
            $table->string('gateway_type')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->string('attachment')->nullable();
            $table->string('invoice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrolments');
    }
};
