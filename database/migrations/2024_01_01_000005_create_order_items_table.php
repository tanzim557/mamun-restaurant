<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('order_id', 36);
            $table->string('menu_item_name', 100);
            $table->integer('quantity')->default(1);
            $table->float('price');

            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
