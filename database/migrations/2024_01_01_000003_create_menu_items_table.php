<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_item', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->float('price');
            $table->string('image', 255)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('category_id', 36);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('category_id')->references('id')->on('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item');
    }
};
