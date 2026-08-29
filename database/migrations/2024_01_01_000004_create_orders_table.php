<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('customer_name', 100);
            $table->string('phone_number', 20);
            $table->text('address');
            $table->float('total_amount')->default(0);
            $table->string('status', 50)->default('PENDING');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
