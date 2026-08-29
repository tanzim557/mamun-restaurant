<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name', 100)->nullable();
            $table->string('email', 120)->unique();
            $table->string('password', 200);
            $table->string('role', 20)->default('ADMIN');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
