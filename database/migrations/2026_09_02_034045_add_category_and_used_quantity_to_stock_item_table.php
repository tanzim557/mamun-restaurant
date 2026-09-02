<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_item', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_item', 'category')) {
                $table->string('category', 50)->default('কাঁচামাল')->after('name');
            }
            if (!Schema::hasColumn('stock_item', 'used_quantity')) {
                $table->float('used_quantity')->default(0)->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_item', function (Blueprint $table) {
            if (Schema::hasColumn('stock_item', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('stock_item', 'used_quantity')) {
                $table->dropColumn('used_quantity');
            }
        });
    }
};
