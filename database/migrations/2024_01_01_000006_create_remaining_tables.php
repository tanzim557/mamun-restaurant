<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('customer_name', 100);
            $table->string('phone_number', 20);
            $table->string('email', 120)->nullable();
            $table->string('date', 20);
            $table->string('time', 20);
            $table->integer('guests');
            $table->text('special_request')->nullable();
            $table->string('status', 20)->default('PENDING');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('gallery_image', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('url', 255);
            $table->string('caption', 255)->nullable();
            $table->string('album', 50)->default('Food');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('site_setting', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('key', 100)->unique();
            $table->text('value');
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('employee', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name', 100);
            $table->string('position', 100);
            $table->string('phone', 20)->nullable();
            $table->float('salary')->default(0);
            $table->float('salary_paid')->default(0);
            $table->float('salary_due')->default(0);
            $table->string('join_date', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('customer_due', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name', 100);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->float('total_due')->default(0);
            $table->float('paid_amount')->default(0);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('daily_ledger', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('date', 20);
            $table->float('total_sales')->default(0);
            $table->float('customer_paid')->default(0);
            $table->float('market_expense')->default(0);
            $table->float('salary_paid')->default(0);
            $table->float('personal_expense')->default(0);
            $table->float('customer_due_given')->default(0);
            $table->float('shomiti_expense')->default(0);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('stock_item', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name', 100);
            $table->float('quantity')->default(0);
            $table->string('unit', 20);
            $table->float('min_quantity')->default(5);
            $table->float('last_price')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_item');
        Schema::dropIfExists('daily_ledger');
        Schema::dropIfExists('customer_due');
        Schema::dropIfExists('employee');
        Schema::dropIfExists('site_setting');
        Schema::dropIfExists('gallery_image');
        Schema::dropIfExists('reservation');
    }
};
